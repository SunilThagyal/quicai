<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\ApiCall;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sampleEndpoint(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'API token required'], 401);
        }

        $apiToken = ApiToken::where('token', $token)
                           ->where('is_active', true)
                           ->first();

        if (!$apiToken) {
            return response()->json(['error' => 'Invalid API token'], 401);
        }

        $user = $apiToken->user;

        if ($user->credits < $user->credit_per_call) {
            return response()->json(['error' => 'Insufficient credits'], 402);
        }

        // Deduct credits
        $user->credits -= $user->credit_per_call;
        $user->save();

        // Log API call
        ApiCall::create([
            'user_id' => $user->id,
            'api_token_id' => $apiToken->id,
            'endpoint' => $request->path(),
            'credits_used' => $user->credit_per_call,
            'request_data' => $request->all(),
            'response_data' => ['message' => 'API call successful'],
            'ip_address' => $request->ip(),
            'called_at' => now()
        ]);

        // Update token last used
        $apiToken->update(['last_used_at' => now()]);

        return response()->json([
            'message' => 'API call successful',
            'remaining_credits' => $user->credits,
            'data' => [
                'timestamp' => now(),
                'user_id' => $user->id,
                'credits_used' => $user->credit_per_call
            ]
        ]);
    }
}
