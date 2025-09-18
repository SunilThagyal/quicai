<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $currentPlan = $user->currentPlan();
        $apiTokens = $user->apiTokens()->where('is_active', true)->get();
        $recentApiCalls = $user->apiCalls()->latest()->limit(10)->get();

        return view('dashboard', compact('user', 'currentPlan', 'apiTokens', 'recentApiCalls'));
    }
}
