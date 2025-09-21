@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold text-dark mb-3 display-4">Choose Your Perfect Plan</h2>
            <p class="text-muted fs-5">Select a plan that fits your API usage needs. All plans include secure token-based authentication.</p>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($plans as $index => $plan)
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                <div class="card plan-card h-100 {{ $index == 1 ? 'plan-popular' : 'shadow-sm' }} fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                    @if($index == 1)
                        <div class="best-value-ribbon">
                            <span class="ribbon-text">Best Value</span>
                        </div>
                    @endif
                    <div class="card-body p-5 d-flex flex-column text-center">
                        <div class="mb-4">
                            <h4 class="fw-bold mb-2 text-dark">{{ $plan->name }}</h4>
                            <div class="display-4 fw-bold text-primary mb-1">
                                $<span class="plan-price">{{ number_format($plan->price, 0) }}</span>
                            </div>
                            <p class="text-muted small">one-time payment per set</p>
                        </div>

                        <div class="mb-4">
                            <div class="stats-box rounded-3 p-3 mb-3">
                                <div class="row text-center g-0">
                                    <div class="col-6 border-end border-secondary">
                                        <div class="fs-4 fw-bold text-primary plan-credits">{{ number_format($plan->credits) }}</div>
                                        <small class="text-muted">Total Credits</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="fs-4 fw-bold text-success">{{ $plan->credit_per_call }}</div>
                                        <small class="text-muted">Credits/Call</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted px-4">{{ $plan->description }}</p>
                        </div>

                        @if($plan->features)
                            <ul class="list-unstyled text-start mb-4 mx-auto plan-features">
                                @foreach(json_decode($plan->features) as $feature)
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>{{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-auto w-100">
                            @if($userPlan && $userPlan->plan_id == $plan->id && $userPlan->status == 'completed')
                                <div class="mb-3">
                                    <button class="btn btn-success w-100 rounded-pill py-3" disabled>
                                        <i class="fas fa-check me-2"></i>Current Plan
                                    </button>
                                </div>
                                <form action="{{ route('payment.create', $plan) }}" method="POST" class="paypal-form" data-price="{{ $plan->price }}" data-credits="{{ $plan->credits }}">
                                    @csrf
                                    <div class="d-flex align-items-center justify-content-center mb-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 quantity-btn" data-action="minus" aria-label="Decrease quantity">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" name="quantity" class="form-control form-control-sm text-center quantity-input border-0" value="1" readonly style="width: 60px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2 quantity-btn" data-action="plus" aria-label="Increase quantity">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary w-100 rounded-pill pay-btn py-3">
                                        <span class="btn-text">
                                            <i class="fas fa-shopping-cart me-2"></i>Buy Again
                                        </span>
                                        <span class="loading-state" style="display:none;">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Processing...
                                        </span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('payment.create', $plan) }}" method="POST" class="paypal-form" data-price="{{ $plan->price }}" data-credits="{{ $plan->credits }}">
                                    @csrf
                                    <div class="d-flex align-items-center justify-content-center mb-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm me-2 quantity-btn" data-action="minus" aria-label="Decrease quantity">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" name="quantity" class="form-control form-control-sm text-center quantity-input border-0" value="1" readonly style="width: 60px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2 quantity-btn" data-action="plus" aria-label="Increase quantity">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn {{ $index == 1 ? 'btn-primary' : 'btn-outline-primary' }} w-100 rounded-pill pay-btn py-3">
                                        <span class="btn-text">
                                            <i class="fab fa-paypal me-2"></i>Pay with PayPal
                                        </span>
                                        <span class="loading-state" style="display:none;">
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

    <div class="row mt-5 pt-5">
        <div class="col-12">
            <h3 class="fw-bold text-center mb-4 display-6">Frequently Asked Questions</h3>
        </div>
        <div class="col-lg-8 mx-auto">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Do credits expire?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Credits expire based on your plan validity period (30-90 days). You can see your expiration date in your dashboard.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Can I upgrade my plan?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! You can purchase a new plan at any time. Your existing credits will remain valid until their expiration date.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Is my payment secure?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Absolutely! All payments are processed securely through PayPal. We don't store any payment information on our servers.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --light-bg: #f8f9fa;
        --dark-text: #212529;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f2f5;
    }

    /* General Card Styles */
    .plan-card {
        border-radius: 1.5rem;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
        z-index: 1; /* Ensure card content is on top */
    }

    .plan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    /* Popular Plan Styles */
    .plan-popular {
        background: linear-gradient(145deg, #ffffff, #f0f2f5);
        border: 2px solid var(--primary-color) !important;
        position: relative;
    }

    .plan-popular::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 200%;
        height: 100%;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.5) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        transform: skewX(-20deg);
        animation: shimmer 8s infinite ease-in-out;
        z-index: -1; /* Place shimmer behind content */
    }

    @keyframes shimmer {
        0% {
            left: -100%;
        }
        50% {
            left: 50%;
        }
        100% {
            left: 100%;
        }
    }

    /* Ribbon */
    .best-value-ribbon {
        position: absolute;
        top: 20px;
        right: -30px;
        background: var(--primary-color);
        color: white;
        padding: 5px 30px;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.8rem;
        transform: rotate(45deg);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Stat Box */
    .stats-box {
        background-color: var(--light-bg);
        border: 1px dashed var(--secondary-color);
    }

    /* Features List */
    .plan-features li {
        color: var(--dark-text);
    }

    .plan-features i {
        color: #28a745;
    }

    /* Buttons */
    .pay-btn {
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .pay-btn:hover {
        transform: translateY(-2px);
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid var(--secondary-color);
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .quantity-input {
        background-color: transparent !important;
        font-weight: bold;
        color: var(--dark-text);
        border-color: var(--secondary-color) !important;
    }

    /* Accordion */
    .accordion-item {
        border-radius: 0.75rem !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .accordion-button {
        background-color: #e9ecef;
        border-radius: 0.75rem !important;
        color: var(--dark-text);
        font-weight: bold;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--primary-color);
        color: white;
    }
</style>

<script>
    (function(){
        function init() {
            // Handle PayPal button loading state
            var forms = document.querySelectorAll('.paypal-form');
            forms.forEach(function(form){
                if (form.dataset.paypalHandlerAttached === '1') return;
                form.dataset.paypalHandlerAttached = '1';

                form.addEventListener('submit', function(){
                    var btn = form.querySelector('button[type="submit"]');
                    if (!btn) return;
                    var btnText = btn.querySelector('.btn-text');
                    var loadingState = btn.querySelector('.loading-state');
                    if (btnText && loadingState) {
                        btnText.style.display = 'none';
                        loadingState.style.display = 'inline-block';
                        btn.disabled = true;
                    }
                });
            });

            // Handle quantity buttons and price update
            document.querySelectorAll('.paypal-form').forEach(form => {
                const quantityInput = form.querySelector('.quantity-input');
                const priceElement = form.closest('.card-body').querySelector('.plan-price');
                const creditsElement = form.closest('.card-body').querySelector('.plan-credits');
                const originalPrice = parseFloat(form.dataset.price);
                const originalCredits = parseInt(form.dataset.credits);

                form.querySelectorAll('.quantity-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        let quantity = parseInt(quantityInput.value);
                        if (button.dataset.action === 'plus') {
                            quantity += 1;
                        } else if (button.dataset.action === 'minus' && quantity > 1) {
                            quantity -= 1;
                        }
                        quantityInput.value = quantity;

                        priceElement.textContent = (originalPrice * quantity).toLocaleString();
                        creditsElement.textContent = (originalCredits * quantity).toLocaleString();
                    });
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
