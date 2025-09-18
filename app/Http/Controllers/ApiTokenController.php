<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tokens = auth()->user()->apiTokens()->latest()->get();
        return view('api.tokens', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $token = ApiToken::create([
            'user_id' => auth()->id(),
            'token' => ApiToken::generateToken(),
            'name' => $request->name,
            'is_active' => true
        ]);

        return redirect()->route('api.tokens.index')
                        ->with('success', 'API token created successfully!');
    }

    public function destroy(ApiToken $token)
    {
        $this->authorize('delete', $token);

        $token->update(['is_active' => false]);

        return redirect()->route('api.tokens.index')
                        ->with('success', 'API token deactivated successfully!');
    }
}
