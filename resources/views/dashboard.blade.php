@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-muted">Account overview and recent activity — concise, actionable, and secure.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @if(!$user->hasActivePlan())
                <a href="{{ route('plans.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-box-open me-2"></i> Choose a Plan
                </a>
            @else
                <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Billing
                </a>
            @endif
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card card-dark-gradient text-white shadow">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">Available Credits</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($user->credits) }}</h3>
                        </div>
                        <div class="     bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-coins text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card card-secondary-gradient text-white shadow">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">Credits per Call</h6>
                            <h3 class="fw-bold mb-0">{{ $user->credit_per_call }}</h3>
                        </div>
                        <div class="     bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-exchange-alt text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card card-primary-gradient text-white shadow">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">API Calls Today</h6>
                            <h3 class="fw-bold mb-0">{{ $user->apiCalls()->whereDate('created_at', today())->count() }}</h3>
                        </div>
                        <div class="     bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-chart-bar text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card card-success-gradient text-white shadow">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-2">Active Tokens</h6>
                            <h3 class="fw-bold mb-0">{{ $apiTokens->count() }}</h3>
                        </div>
                        <div class="     bg-opacity-20 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-key text-white fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Current Plan --}}
        <div class="col-lg-6">
            <div class="card shadow-sm fade-in-up border-0 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-star text-warning me-2"></i>Current Plan
                    </h5>
                </div>
                <div class="card-body">
                    @if($currentPlan)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background-color: var(--light-bg);">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $currentPlan->plan->name }}</h6>
                                <p class="text-muted small mb-0">Expires: {{ $currentPlan->expires_at->format('M d, Y') }}</p>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Credits Used</span>
                                <span>{{ number_format($currentPlan->plan->credits - $user->credits) }} / {{ number_format($currentPlan->plan->credits) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ (($currentPlan->plan->credits - $user->credits) / $currentPlan->plan->credits) * 100 }}%; background-color: var(--primary);"></div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="{{ route('plans.index') }}" class="btn btn-outline-primary btn-sm me-2">Change Plan</a>
                            <a href="{{ route('plans.index') }}" class="btn btn-primary btn-sm">Manage</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-info" style="font-size: 2rem;"></i>
                            <h6 class="fw-bold mt-3">No Active Plan</h6>
                            <p class="text-muted mb-3">You don't have an active plan. Choose one to start using the API.</p>
                            <a href="{{ route('plans.index') }}" class="btn btn-primary">Choose Plan</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- API Tokens --}}
        <div class="col-lg-6">
            <div class="card shadow-sm fade-in-up border-0 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-key text-dark me-2"></i>API Tokens
                    </h5>
                </div>
                <div class="card-body">
                    @if($apiTokens->count() > 0)
                        @foreach($apiTokens as $token)
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2" style="background-color: var(--light-bg);">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $token->name }}</h6>
                                    <p class="text-muted small mb-0">
                                        Last used: {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                    </p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2">Active</span>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyToken('{{ $token->token }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-key text-info" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-3">No API tokens available. Purchase a plan to get your API token.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent API Calls --}}
        <div class="col-12">
            <div class="card shadow-sm fade-in-up border-0">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-history text-dark me-2"></i>Recent API Calls
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentApiCalls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
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
                                            <td><span class="badge bg-primary">{{ $call->credits_used }}</span></td>
                                            <td>{{ $call->ip_address }}</td>
                                            <td>{{ $call->called_at->format('M d, Y H:i') }}</td>
                                            <td><span class="badge bg-success">Success</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line text-info" style="font-size: 2rem;"></i>
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
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(token)
                .then(() => {
                    showToast('API token copied to clipboard!', 'success');
                })
                .catch(err => {
                    console.error('Failed to copy text: ', err);
                    showToast('Unable to copy token. Please try again.', 'danger');
                });
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = token;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showToast('API token copied to clipboard!', 'success');
            } catch (err) {
                showToast('Unable to copy token. Please copy it manually.', 'danger');
            } finally {
                document.body.removeChild(textArea);
            }
        }
    }

    function showToast(message, type = 'info') {
        const containerId = 'toast-container';
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        let bgClass = `bg-${type}`;
        let iconClass = 'fas fa-info-circle';
        if (type === 'success') iconClass = 'fas fa-check-circle';
        if (type === 'danger') iconClass = 'fas fa-exclamation-circle';

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white border-0 show ${bgClass}`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="${iconClass} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        container.appendChild(toastEl);

        setTimeout(() => {
            if (toastEl.parentNode === container) {
                new bootstrap.Toast(toastEl).hide();
            }
        }, 3000);

        toastEl.addEventListener('hidden.bs.toast', () => {
            if (toastEl.parentNode === container) {
                container.removeChild(toastEl);
            }
        });
    }
</script>
@endsection
