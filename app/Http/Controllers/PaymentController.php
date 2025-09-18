<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create PayPal order and redirect user to approval URL
     */
    public function createPayment(Request $request, Plan $plan)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $accessToken = $provider->getAccessToken();

        if (! $accessToken) {
            Log::error('PayPal: could not obtain access token', ['config' => config('paypal')]);
            return redirect()->route('plans.index')->with('error', 'Payment gateway error (token).');
        }

        // Ensure price is properly formatted
        $amountValue = number_format($plan->price, 2, '.', '');

        $orderPayload = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ],
            'purchase_units' => [
                0 => [
                    'reference_id' => (string) $plan->id,
                    'description'  => $plan->description,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amountValue,
                    ],
                ],
            ],
        ];

        try {
            $response = $provider->createOrder($orderPayload);

            if (isset($response['id']) && $response['id'] != null) {
                // Persist a pending UserPlan to reconcile later
                $userPlan = UserPlan::create([
                    'user_id' => auth()->id(),
                    'plan_id' => $plan->id,
                    'paypal_order_id' => $response['id'],
                    'amount_paid' => $plan->price,
                    'status' => 'pending',
                ]);

                // find approval link and redirect
                if (isset($response['links']) && is_array($response['links'])) {
                    foreach ($response['links'] as $link) {
                        if (isset($link['rel']) && $link['rel'] === 'approve') {
                            return redirect()->away($link['href']);
                        }
                    }
                }

                // No approve link found
                Log::error('PayPal createOrder returned no approve link', ['response' => $response]);
                return redirect()->route('plans.index')->with('error', 'Payment gateway did not return approval link.');
            }

            Log::error('PayPal createOrder failed', ['response' => $response]);
            return redirect()->route('plans.index')->with('error', $response['message'] ?? 'Unable to create PayPal order.');
        } catch (\Exception $e) {
            Log::error('PayPal createOrder exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('plans.index')->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * PayPal cancel URL
     */
    public function cancelPayment(Request $request)
    {
        // Optionally you can reconcile/mark pending orders canceled based on token param
        return redirect()->route('plans.index')->with('info', 'You cancelled the payment.');
    }

    /**
     * Capture the PayPal order after user approval
     */
    public function executePayment(Request $request)
    {
        // Orders API returns 'token' query param for the order id
        $orderId = $request->get('token') ?? $request->get('orderId') ?? null;

        if (! $orderId) {
            return redirect()->route('plans.index')->with('error', 'Missing PayPal order token.');
        }

        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        try {
            $response = $provider->capturePaymentOrder($orderId);

            // Save capture response for debugging
            Log::info('PayPal capture response', ['order_id' => $orderId, 'response' => $response]);

            // Response status is usually in $response['status']
            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                // Find our pending UserPlan by paypal_order_id
                $userPlan = UserPlan::where('paypal_order_id', $orderId)->first();

                if (! $userPlan) {
                    Log::warning('PayPal capture completed but no matching UserPlan found', ['order_id' => $orderId, 'response' => $response]);
                    return redirect()->route('plans.index')->with('error', 'Order completed but not found in our records. Please contact support.');
                }

                $plan = $userPlan->plan;

                // Verify captured amount equals expected
                // navigate response to find amount value. The structure can be nested:
                // purchase_units -> payments -> captures -> [0] -> amount -> value
                $capturedAmount = null;
                if (!empty($response['purchase_units'][0]['payments']['captures'][0]['amount']['value'])) {
                    $capturedAmount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                } elseif (!empty($response['purchase_units'][0]['amount']['value'])) {
                    // fallback
                    $capturedAmount = $response['purchase_units'][0]['amount']['value'];
                } elseif (!empty($response['purchase_units'][0]['payments']['captures'][0]['amount']['value'])) {
                    $capturedAmount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                }

                $expected = number_format($plan->price, 2, '.', '');

                if ($capturedAmount === null || number_format((float)$capturedAmount, 2, '.', '') !== $expected) {
                    Log::error('PayPal amount mismatch', [
                        'order_id' => $orderId,
                        'expected' => $expected,
                        'captured' => $capturedAmount,
                        'response' => $response
                    ]);
                    return redirect()->route('plans.index')->with('error', 'Payment amount mismatch.');
                }

                // Mark user plan completed
                $userPlan->update([
                    'paypal_payment_id' => $response['id'] ?? $orderId,
                    'status' => 'completed',
                    'expires_at' => now()->addDays(30),
                ]);

                // Update user credits and credit_per_call
                $user = auth()->user();
                $user->update([
                    'credits' => ($user->credits ?? 0) + $plan->credits,
                    'credit_per_call' => $plan->credit_per_call,
                ]);

                // Create API token
                ApiToken::create([
                    'user_id' => $user->id,
                    'token' => method_exists(ApiToken::class, 'generateToken') ? ApiToken::generateToken() : bin2hex(random_bytes(32)),
                    'name' => $plan->name . ' Token',
                    'is_active' => true,
                ]);

                return redirect()->route('dashboard')->with('success', 'Payment successful! Your plan has been activated.');
            }

            // If status not completed
            Log::warning('PayPal capture not completed', ['order_id' => $orderId, 'response' => $response]);
            return redirect()->route('plans.index')->with('error', $response['message'] ?? 'Payment not completed.');
        } catch (\Exception $e) {
            Log::error('PayPal capture exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('plans.index')->with('error', 'Payment capture failed: ' . $e->getMessage());
        }
    }
}
