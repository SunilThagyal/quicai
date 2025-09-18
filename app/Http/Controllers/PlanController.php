<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $plans = Plan::where('is_active', true)->get();
        $userPlan = auth()->user()->currentPlan();

        return view('plans.index', compact('plans', 'userPlan'));
    }

    public function show(Plan $plan)
    {
        return view('plans.show', compact('plan'));
    }
}
