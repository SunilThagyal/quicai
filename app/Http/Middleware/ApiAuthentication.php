<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;

class ApiAuthentication
{
    public function handle(Request $request, Closure $next)
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

        $request->merge([
            'api_user' => $user,
            'api_token' => $apiToken
        ]);

        return $next($request);
    }
}
