@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="text-muted">Here's your account overview and recent activity</p>
                </div>
                @if(!$user->hasActivePlan())
                    <a href="{{ route('plans.index') }}" class="btn" style="background-color: #F4D03F; color: #36454F;">
                        <i class="fas fa-plus me-2"></i>Choose a Plan
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #1E8449, #28B463);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">Available Credits</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($user->credits) }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-coins text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #1E8449, #28B463);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">Credits per Call</h6>
                            <h3 class="fw-bold mb-0">{{ $user->credit_per_call }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-exchange-alt text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #1E8449, #28B463);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">API Calls Today</h6>
                            <h3 class="fw-bold mb-0">{{ $user->apiCalls()->whereDate('created_at', today())->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-chart-bar text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #1E8449, #28B463);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">Active Tokens</h6>
                            <h3 class="fw-bold mb-0">{{ $apiTokens->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-key text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm fade-in-up border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-star" style="color: #F4D03F;"></i>Current Plan
                    </h5>
                </div>
                <div class="card-body">
                    @if($currentPlan)
                        <div class="d-flex align-items-center justify-content-between p-3" style="background-color: #FAFAF8; border-radius: 0.5rem;">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $currentPlan->plan->name }}</h6>
                                <p class="text-muted small mb-0">Expires: {{ $currentPlan->expires_at->format('M d, Y') }}</p>
                            </div>
                            <span class="badge" style="background-color: #28B463;">Active</span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Credits Used</span>
                                <span>{{ number_format($currentPlan->plan->credits - $user->credits) }} / {{ number_format($currentPlan->plan->credits) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ (($currentPlan->plan->credits - $user->credits) / $currentPlan->plan->credits) * 100 }}%; background-color: #1E8449;"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle" style="color: #F4D03F; font-size: 2rem;"></i>
                            <h6 class="fw-bold mt-3">No Active Plan</h6>
                            <p class="text-muted mb-3">You don't have an active plan. Choose one to start using the API.</p>
                            <a href="{{ route('plans.index') }}" class="btn" style="background-color: #1E8449; color: #fff;">Choose Plan</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm fade-in-up border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-key" style="color: #1E8449;"></i>API Tokens
                    </h5>
                </div>
                <div class="card-body">
                    @if($apiTokens->count() > 0)
                        @foreach($apiTokens as $token)
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 mb-2" style="border-color: #E2E2E2 !important;">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $token->name }}</h6>
                                    <p class="text-muted small mb-0">
                                        Last used: {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                    </p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge" style="background-color: #28B463; margin-right: 0.5rem;">Active</span>
                                    <button class="btn btn-sm" style="color: #36454F; border: 1px solid #D1D1D1;" onclick="copyToken('{{ $token->token }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-key" style="color: #D1D1D1; font-size: 2rem;"></i>
                            <p class="text-muted mt-3">No API tokens available. Purchase a plan to get your API token.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm fade-in-up border-0">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-history" style="color: #1E8449;"></i>Recent API Calls
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentApiCalls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead style="background-color: #FAFAF8;">
                                    <tr>
                                        <th>Endpoint</th>
                                        <th>Credits Used</th>
                                        <th>IP Address</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentApiCalls as $call)
                                        <tr style="border-bottom: 1px solid #E2E2E2;">
                                            <td><code>/{{ $call->endpoint }}</code></td>
                                            <td><span class="badge" style="background-color: #28B463;">{{ $call->credits_used }}</span></td>
                                            <td>{{ $call->ip_address }}</td>
                                            <td>{{ $call->called_at->format('M d, Y H:i') }}</td>
                                            <td><span class="badge" style="background-color: #F4D03F; color: #36454F;">Success</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line" style="color: #D1D1D1; font-size: 2rem;"></i>
                            <h6 class="fw-bold mt-3">No API Calls Yet</h6>
                            <p class="text-muted">Your API usage will appear here once you start making calls.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyToken(token) {
        navigator.clipboard.writeText(token).then(function() {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3'; // Changed position to bottom-right
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="toast show" role="alert" style="background-color: #1E8449; color: #fff; border: none;">
                    <div class="toast-header" style="border-bottom: none;">
                        <i class="fas fa-check-circle me-2" style="color: #F4D03F !important;"></i>
                        <strong class="me-auto" style="color: #fff;">Success</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        API token copied to clipboard!
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                const toastEl = new bootstrap.Toast(toast.querySelector('.toast'));
                toastEl.hide();
                toast.addEventListener('hidden.bs.toast', () => {
                    document.body.removeChild(toast);
                });
            }, 3000);
        });
    }
</script>
@endsection
