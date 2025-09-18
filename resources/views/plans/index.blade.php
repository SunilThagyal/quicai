@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold text-dark mb-3">Choose Your Perfect Plan</h2>
            <p class="text-muted fs-5">Select a plan that fits your API usage needs. All plans include secure token-based authentication.</p>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($plans as $index => $plan)
            <div class="col-lg-4 col-md-6">
                <div class="card plan-card h-100 {{ $index == 1 ? 'plan-popular' : '' }} fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="card-body p-5 d-flex flex-column text-center">
                        <div class="mb-4">
                            <h4 class="fw-bold mb-2">{{ $plan->name }}</h4>
                            <div class="display-4 fw-bold text-primary mb-1">${{ number_format($plan->price, 0) }}</div>
                            <p class="text-muted">one-time payment</p>
                        </div>

                        <div class="mb-4">
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <div class="row text-center">
                                    <div class="col-6 border-end">
                                        <div class="fs-4 fw-bold text-primary">{{ number_format($plan->credits) }}</div>
                                        <small class="text-muted">Total Credits</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="fs-4 fw-bold text-success">{{ $plan->credit_per_call }}</div>
                                        <small class="text-muted">Credits/Call</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted">{{ $plan->description }}</p>
                        </div>

                        @if($plan->features)
                            <ul class="list-unstyled mb-4">
                                @foreach(json_decode($plan->features) as $feature)
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>{{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-auto w-100">
                            @if($userPlan && $userPlan->plan_id == $plan->id && $userPlan->status == 'completed')
                                <button class="btn btn-success w-100" disabled>
                                    <i class="fas fa-check me-2"></i>Current Plan
                                </button>
                            @else
                                <form action="{{ route('payment.create', $plan) }}" method="POST" class="paypal-form">
                                    @csrf
                                    <button type="submit" class="btn {{ $index == 1 ? 'btn-primary' : 'btn-outline-primary' }} w-100 pay-btn">
                                        <span class="btn-text">
                                            <i class="fab fa-paypal me-2"></i>Pay with PayPal
                                        </span>
                                        <span class="loading" style="display:none;">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Processing...
                                        </span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- FAQ Section -->
    <div class="row mt-5 pt-5">
        <div class="col-12">
            <h3 class="fw-bold text-center mb-4">Frequently Asked Questions</h3>
        </div>
        <div class="col-lg-8 mx-auto">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Do credits expire?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Credits expire based on your plan validity period (30-90 days). You can see your expiration date in your dashboard.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Can I upgrade my plan?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! You can purchase a new plan at any time. Your existing credits will remain valid until their expiration date.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Is my payment secure?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Absolutely! All payments are processed securely through PayPal. We don't store any payment information on our servers.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inline script to toggle loading state -->
<script>
(function(){
  function init() {
    var forms = document.querySelectorAll('.paypal-form');
    forms.forEach(function(form){
      if (form.dataset.paypalHandlerAttached === '1') return;
      form.dataset.paypalHandlerAttached = '1';

      form.addEventListener('submit', function(){
        var btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        var btnText = btn.querySelector('.btn-text');
        var loading = btn.querySelector('.loading');
        if (btnText && loading) {
          btnText.style.display = 'none';
          loading.style.display = 'inline-block';
          btn.disabled = true;
        }
      });
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>
@endsection
