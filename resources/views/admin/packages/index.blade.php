@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="container-fluid px-0">
        <div class="subscription-hero bg-gradient-primary">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="display-4 text-white mb-3">Choose Your Perfect Plan</h1>
                        <p class="lead text-white-50 mb-4">Flexible pricing designed to grow with your business. Switch or cancel anytime.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="billing-toggle-container bg-white rounded-pill p-1 d-inline-block shadow-sm">
                            <div class="form-check form-switch d-flex align-items-center justify-content-between m-0">
                                <span class="px-3 fw-medium {{ !request()->has('annual') ? 'text-primary' : 'text-muted' }}">Monthly</span>
                                <input class="form-check-input mx-0" type="checkbox" id="billingToggle" {{ request()->has('annual') ? 'checked' : '' }}>
                                <label class="form-check-label px-3 fw-medium {{ request()->has('annual') ? 'text-primary' : 'text-muted' }}" for="billingToggle">
                                    Annual <span class="badge bg-success ms-1">Save 10%</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($hasActiveSubscription)
        <div class="container mt-4">
            <div class="alert alert-info bg-soft-info border-left-info border-left-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Your Current Subscription</h5>
                        <p class="mb-0">
                            {{ ucfirst($latestActiveSubscription->package_type) }} Plan ({{ $latestActiveSubscription->billing_cycle }})
                            active until {{ \Carbon\Carbon::parse($latestActiveSubscription->end_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="container my-5">
            <div class="row g-4 justify-content-center">
                @foreach($packages as $package)
                <div class="col-lg-4 col-md-6">
                    <div class="card pricing-card h-100 border-0 shadow-sm overflow-hidden {{ $package['popular'] ?? false ? 'popular-plan' : '' }}">
                        @if($package['popular'] ?? false)
                        <div class="popular-badge bg-gradient-success text-white py-1 text-center">
                            <i class="fas fa-crown me-2"></i> MOST POPULAR
                        </div>
                        @endif

                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <h3 class="mb-0">{{ $package['title'] }}</h3>
                                @if($package['popular'] ?? false)
                                <span class="badge bg-soft-primary text-primary rounded-pill">Best Value</span>
                                @endif
                            </div>

                            <div class="price-display mb-4">
                                <div class="d-flex align-items-end">
                                    <span class="price-currency h5 mb-0">AED</span>
                                    <span class="price-amount price-monthly display-4 fw-bold mx-1">{{ $package['monthly_price'] }}</span>
                                    <span class="price-amount price-annually d-none display-4 fw-bold mx-1">{{ $package['annual_price'] }}</span>
                                    <span class="price-period h5 mb-2 text-muted">/<span class="billing-period">month</span></span>
                                </div>
                                @if($package['annual_price'] < $package['monthly_price'] * 12)
                                <div class="price-savings text-success mt-1">
                                    <i class="fas fa-chart-line me-1"></i> Save {{ round((1 - $package['annual_price']/($package['monthly_price'] * 12)) * 100) }}% annually
                                </div>
                                @endif
                            </div>

                            <ul class="feature-list list-unstyled mb-4">
                                @foreach($package['features'] as $feature)
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="card-footer bg-transparent border-top-0 pt-0 pb-4 px-4">
                            <button class="btn btn-gradient-primary w-100 subscribe-btn py-3"
                                    data-package="{{ $package['title'] }}"
                                    data-price-monthly="{{ $package['monthly_price'] }}"
                                    data-price-annual="{{ $package['annual_price'] }}">
                                Get Started
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="container my-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h4 class="mb-0">Plan Comparison</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th style="width: 30%">Feature</th>
                                @foreach($packages as $package)
                                <th class="text-center {{ $package['popular'] ?? false ? 'bg-soft-primary' : '' }}" style="width: {{ 70/count($packages) }}%">
                                    {{ $package['title'] }}
                                </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php
                            $allFeatures = [];
                            foreach($packages as $package) {
                            $allFeatures = array_merge($allFeatures, $package['features']);
                            }
                            $allFeatures = array_unique($allFeatures);
                            @endphp

                            @foreach($allFeatures as $feature)
                            <tr>
                                <td>{{ $feature }}</td>
                                @foreach($packages as $package)
                                <td class="text-center {{ $package['popular'] ?? false ? 'bg-soft-primary' : '' }}">
                                    @if(in_array($feature, $package['features']))
                                    <i class="fas fa-check-circle text-success"></i>
                                    @else
                                    <i class="fas fa-times-circle text-muted opacity-50"></i>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title">Subscribe to <span id="packageTitle"></span> Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm">
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="mb-0">AED <span id="packagePrice" class="fw-bold"></span>/<span id="packagePeriod" class="text-muted">month</span></h3>
                                <small class="text-muted">Billed <span id="billingFrequency">monthly</span></small>
                            </div>
                            <div id="paymentProcessing" class="d-none">
                                <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="ms-2">Processing...</span>
                            </div>
                        </div>

                        <input type="hidden" name="package_type" id="packageType">
                        <input type="hidden" name="duration" id="packageDuration">
                        <input type="hidden" name="amount" id="packageAmount">
                        <input type="hidden" name="currency" value="aed">
                        <input type="hidden" name="client_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="existing_subscription_id" value="{{ $latestActiveSubscription->id ?? '' }}">
                        <input type="hidden" name="existing_subscription_end_date" value="{{ $latestActiveSubscription->end_date ?? '' }}">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="{{ auth()->user()->name }}" required>
                                    <label for="name">Full Name On Card</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ auth()->user()->email }}" readonly required>
                                    <label for="email">Email Confirmation</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone" value="{{ auth()->user()->phone }}" required>
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="h-100 d-flex align-items-center">
                                    @if($hasActiveSubscription)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="package_date" id="startNow" value="now">
                                        <label class="form-check-label" for="startNow">Start Now</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="package_date" id="startOnExpiry" value="expiry" checked>
                                        <label class="form-check-label" for="startOnExpiry">
                                            After {{ \Carbon\Carbon::parse($latestActiveSubscription->end_date)->format('M d') }}
                                        </label>
                                    </div>
                                    @else
                                    <div class="text-muted">Subscription starts immediately</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label mb-2">Card Details</label>
                            <div id="cardElement" class="form-control p-3 border rounded"></div>
                            <div id="cardErrors" class="text-danger mt-2 small"></div>
                        </div>

                        <div id="paymentSuccess" class="alert alert-success d-none">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Payment Successful!</h5>
                                    <p class="mb-0">Your subscription has been activated. Redirecting...</p>
                                </div>
                            </div>
                        </div>

                        <div id="paymentError" class="alert alert-danger d-none">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                <div id="paymentErrorMessage"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-gradient-primary px-4">
                            <span class="payment-button-text">Pay AED <span id="submitAmount"></span></span>
                            <span class="payment-button-loader d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Processing...
                        </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_package_index.css') }}">
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    let cardElement;

    $(document).ready(function() {
        $('#billingToggle').change(function() {
            const isAnnual = $(this).is(':checked');
            if (isAnnual) {
                $('.price-monthly').addClass('d-none');
                $('.price-annually').removeClass('d-none');
                $('.billing-period').text('year');
                $('#billingFrequency').text('annually');
            } else {
                $('.price-monthly').removeClass('d-none');
                $('.price-annually').addClass('d-none');
                $('.billing-period').text('month');
                $('#billingFrequency').text('monthly');
            }

            const url = new URL(window.location.href);
            if(isAnnual) {
                url.searchParams.set('annual', 'true');
            } else {
                url.searchParams.delete('annual');
            }
            window.history.replaceState({}, '', url);
        });

        $('#paymentModal').on('shown.bs.modal', function() {
            if (!cardElement) {
                const style = {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        fontFamily: '"Inter", sans-serif',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                };

                cardElement = elements.create('card', {
                    style: style,
                    hidePostalCode: true
                });
                cardElement.mount('#cardElement');

                cardElement.on('change', function(event) {
                    const displayError = document.getElementById('cardErrors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                    } else {
                        displayError.textContent = '';
                    }
                });
            }
        });

        $('#paymentModal').on('hidden.bs.modal', function() {
            if (cardElement) {
                cardElement.unmount();
                cardElement = null;
            }
            $('#paymentForm')[0].reset();
            $('#paymentSuccess').addClass('d-none');
            $('#paymentError').addClass('d-none');
            $('.payment-button-text').removeClass('d-none');
            $('.payment-button-loader').addClass('d-none');
        });

        $('.subscribe-btn').click(function() {
            const packageTitle = $(this).data('package');
            const isAnnual = $('#billingToggle').is(':checked');
            const price = isAnnual ? $(this).data('price-annual') : $(this).data('price-monthly');
            const period = isAnnual ? 'yearly' : 'monthly';

            $('#packageTitle').text(packageTitle);
            $('#packagePrice').text(price);
            $('#packagePeriod').text(isAnnual ? 'year' : 'month');
            $('#packageType').val(packageTitle.toLowerCase());
            $('#packageDuration').val(period);
            $('#packageAmount').val(price);
            $('#submitAmount').text(price);
            $('#billingFrequency').text(isAnnual ? 'annually' : 'monthly');

            $('#paymentModal').modal('show');
        });

        $('#paymentForm').submit(async function(e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#submitBtn');

            $('.payment-button-text').addClass('d-none');
            $('.payment-button-loader').removeClass('d-none');
            $('#paymentProcessing').removeClass('d-none');
            $('#paymentError').addClass('d-none');

            try {
                const response = await $.ajax({
                    url: '{{ route('admin.packages.create-payment-intent') }}',
                    method: 'POST',
                    data: {
                        amount: form.find('#packageAmount').val(),
                        currency: form.find('[name="currency"]').val(),
                        plan: form.find('#packageTitle').text(),
                        billing: $('#packagePeriod').text(),
                        package_date: form.find('[name="package_date"]:checked').val(),
                        existing_subscription_id: form.find('[name="existing_subscription_id"]').val(),
                        existing_subscription_end_date: form.find('[name="existing_subscription_end_date"]').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const { paymentIntent, error } = await stripe.confirmCardPayment(
                    response.clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: form.find('#name').val(),
                                email: form.find('#email').val(),
                                phone: form.find('#phone').val()
                            }
                        }
                    }
                );

                if (error) {
                    throw new Error(error.message);
                }

                if (paymentIntent.status === 'succeeded') {
                    const saveResponse = await $.ajax({
                        url: '{{ route('admin.packages.save-details') }}',
                        method: 'POST',
                        data: {
                            ...form.serializeArray().reduce((obj, item) => {
                                obj[item.name] = item.value;
                                return obj;
                            }, {}),
                            payment_intent_id: paymentIntent.id,
                            startNow: form.find('#startNow').is(':checked') ? 1 : 0,
                            startOnExpiry: form.find('#startOnExpiry').is(':checked') ? 1 : 0
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    if (saveResponse.success) {
                        $('#paymentSuccess').removeClass('d-none');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        throw new Error(saveResponse.message || 'Failed to save subscription details');
                    }
                }
            } catch (error) {
                $('#paymentErrorMessage').text(error.message);
                $('#paymentError').removeClass('d-none');
                console.error('Payment error:', error);
            } finally {
                $('.payment-button-text').removeClass('d-none');
                $('.payment-button-loader').addClass('d-none');
                $('#paymentProcessing').addClass('d-none');
            }
        });
    });
</script>
@endpush
