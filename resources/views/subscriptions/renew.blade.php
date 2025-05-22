@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="container-fluid px-0">
        <div class="row mx-0">
            <div class="col-12 px-4 py-5 renewal-header">
                <div class="d-flex align-items-center">
                    <i class="fas fa-sync-alt me-3"></i>
                    <h1 class="mb-0">Renew Your Subscription</h1>
                </div>
                <p class="mb-0 mt-2">Keep enjoying our premium features without interruption</p>
            </div>
        </div>

        <div class="row mx-0">
            <div class="col-lg-5 px-4 py-4">
                <div class="card current-subscription-card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-info-circle me-2"></i> Current Plan Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="subscription-details">
                            <div class="detail-item">
                                <span class="detail-label">Package:</span>
                                <span class="detail-value">{{ ucfirst($subscription->package_type) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Billing Cycle:</span>
                                <span class="detail-value">{{ ucfirst($subscription->billing_cycle) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Amount:</span>
                                <span class="detail-value">{{ $subscription->amount }} {{ strtoupper($subscription->currency) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value badge bg-{{ $subscription->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Expires:</span>
                                <span class="detail-value">{{ $subscription->end_date->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="renewal-benefits mt-4">
                            <h5><i class="fas fa-star me-2 text-warning"></i> Renewal Benefits</h5>
                            <ul class="benefits-list">
                                <li><i class="fas fa-check-circle text-success me-2"></i> Continuous service without interruption</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Exclusive discounts for loyal customers</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Priority customer support</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 px-4 py-4">
                <div class="card renewal-options-card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-credit-card me-2"></i> Renewal Options</h3>
                    </div>
                    <div class="card-body">
                        <form id="renewalForm">
                            @csrf
                            <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">

                            <div class="option-section mb-4">
                                <h5 class="section-title"><i class="fas fa-calendar-alt me-2"></i> Select Billing Cycle</h5>
                                <div class="billing-options">
                                    <div class="option-card monthly-option">
                                        <input class="form-check-input" type="radio" name="billing_cycle"
                                               id="monthly" value="monthly"
                                               {{ $subscription->billing_cycle === 'monthly' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="monthly">
                                            <div class="option-content">
                                                <span class="option-title">Monthly</span>
                                                <span class="option-price">AED {{ number_format($subscription->amount, 2) }}</span>
                                                <span class="option-duration">per month</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="option-card yearly-option">
                                        <input class="form-check-input" type="radio" name="billing_cycle"
                                               id="yearly" value="yearly"
                                               {{ $subscription->billing_cycle === 'yearly' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yearly">
                                            <div class="option-content">
                                                <span class="option-title">Yearly</span>
                                                <span class="option-price">AED {{ number_format($subscription->amount * 12 * 0.9, 2) }}</span>
                                                <span class="option-duration">per year</span>
                                                <span class="option-badge">Save 10%</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="option-section mb-4">
                                <h5 class="section-title"><i class="fas fa-wallet me-2"></i> Payment Method</h5>

                                @if(!empty($paymentMethods) || !empty($paymentMethodFromIntent))
                                <div class="payment-methods">
                                    @foreach($paymentMethods as $method)
                                    <div class="payment-method-card">
                                        <input class="form-check-input payment-method-radio"
                                               type="radio" name="payment_method"
                                               id="method_{{ $method->id }}"
                                               value="{{ $method->id }}"
                                               data-method="{{ json_encode($method) }}"
                                               {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label" for="method_{{ $method->id }}">
                                            <div class="method-content">
                                                <i class="fab fa-cc-{{ $method->card->brand }} method-icon"></i>
                                                <div class="method-details">
                                                    <span class="method-type">Card ending in {{ $method->card->last4 }}</span>
                                                    <span class="method-expiry">Expires {{ $method->card->exp_month }}/{{ $method->card->exp_year }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach

                                    @if(!empty($paymentMethodFromIntent))
                                    <div class="payment-method-card">
                                        <input class="form-check-input payment-method-radio"
                                               type="radio" name="payment_method"
                                               id="method_intent_{{ $paymentMethodFromIntent->id }}"
                                               value="{{ $paymentMethodFromIntent->id }}"
                                               data-method="{{ json_encode($paymentMethodFromIntent) }}">
                                        <label class="form-check-label" for="method_intent_{{ $paymentMethodFromIntent->id }}">
                                            <div class="method-content">
                                                <i class="fab fa-cc-{{ $paymentMethodFromIntent->card->brand }} method-icon"></i>
                                                <div class="method-details">
                                                    <span class="method-type">Card ending in {{ $paymentMethodFromIntent->card->last4 }}</span>
                                                    <span class="method-expiry">Expires {{ $paymentMethodFromIntent->card->exp_month }}/{{ $paymentMethodFromIntent->card->exp_year }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endif

                                    <div class="payment-method-card new-method">
                                        <input class="form-check-input" type="radio"
                                               name="payment_method" id="new_method" value="new">
                                        <label class="form-check-label" for="new_method">
                                            <div class="method-content">
                                                <i class="fas fa-plus-circle method-icon"></i>
                                                <span class="method-type">Add new payment method</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div id="newCardSection" class="mt-3 d-none">
                                    <div class="card-input-header">
                                        <h6><i class="fas fa-credit-card me-2"></i> Enter Card Details</h6>
                                    </div>
                                    <div id="cardElement" class="form-control p-3 card-input"></div>
                                    <div id="cardErrors" class="text-danger mt-2 error-message"></div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="submitBtn" class="btn btn-primary btn-renew">
                                    <i class="fas fa-sync-alt me-2"></i> Renew Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> Renewal Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="success-icon mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4 class="mb-3">Your subscription has been renewed!</h4>
                <p class="mb-0">A confirmation has been sent to your email address.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fas fa-thumbs-up me-2"></i> Continue to Dashboard
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Header Styles */
    .renewal-header {
        background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        color: white;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .renewal-header i {
        font-size: 2rem;
    }

    /* Card Styles */
    .current-subscription-card, .renewal-options-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Subscription Details */
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
    }

    .detail-value {
        color: #333;
    }

    /* Benefits List */
    .benefits-list {
        list-style: none;
        padding-left: 0;
    }

    .benefits-list li {
        padding: 0.5rem 0;
    }

    /* Option Sections */
    .option-section {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #eaeaea;
    }

    .section-title {
        color: #3a7bd5;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }

    /* Billing Options */
    .billing-options {
        display: flex;
        gap: 15px;
    }

    .option-card {
        flex: 1;
        position: relative;
    }

    .option-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .option-card label {
        display: block;
        padding: 1.5rem;
        background: white;
        border: 2px solid #eaeaea;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-card input[type="radio"]:checked + label {
        border-color: #3a7bd5;
        background-color: #f0f7ff;
    }

    .option-content {
        text-align: center;
    }

    .option-title {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .option-price {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #3a7bd5;
        margin-bottom: 0.25rem;
    }

    .option-duration {
        display: block;
        color: #777;
        font-size: 0.9rem;
    }

    .option-badge {
        display: inline-block;
        background: #4CAF50;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    /* Payment Methods */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .payment-method-card {
        position: relative;
    }

    .payment-method-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .payment-method-card label {
        display: block;
        padding: 1rem 1.5rem;
        background: white;
        border: 2px solid #eaeaea;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method-card input[type="radio"]:checked + label {
        border-color: #3a7bd5;
        background-color: #f0f7ff;
    }

    .method-content {
        display: flex;
        align-items: center;
    }

    .method-icon {
        font-size: 1.75rem;
        color: #3a7bd5;
        margin-right: 1rem;
    }

    .method-details {
        display: flex;
        flex-direction: column;
    }

    .method-type {
        font-weight: 600;
        color: #333;
    }

    .method-expiry {
        color: #777;
        font-size: 0.9rem;
    }

    .new-method .method-icon {
        color: #4CAF50;
    }

    /* Card Input */
    .card-input-header {
        background: #f5f5f5;
        padding: 0.75rem 1rem;
        border-radius: 6px 6px 0 0;
        border: 1px solid #eaeaea;
        border-bottom: none;
    }

    .card-input {
        border-radius: 0 0 6px 6px;
        height: 50px;
        transition: border-color 0.3s;
    }

    /* Button Styles */
    .btn-renew {
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
        border: none;
        box-shadow: 0 4px 15px rgba(58, 123, 213, 0.3);
        transition: all 0.3s ease;
    }

    .btn-renew:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(58, 123, 213, 0.4);
    }

    /* Success Modal */
    .success-icon {
        font-size: 4rem;
        color: #4CAF50;
        margin-bottom: 1.5rem;
    }

    /* Error Message */
    .error-message {
        padding: 0.5rem;
        background: #fff0f0;
        border-radius: 4px;
        border-left: 4px solid #ff5252;
    }
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    let cardElement;
    let isCardMounted = false;

    $(document).ready(function() {
        const style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                },
                iconColor: '#3a7bd5'
            },
            invalid: {
                color: '#ff5252',
                iconColor: '#ff5252'
            }
        };

        cardElement = elements.create('card', {
            style: style,
            hidePostalCode: true
        });

        cardElement.on('change', function(event) {
            const displayError = document.getElementById('cardErrors');
            if (event.error) {
                displayError.textContent = event.error.message;
                $('#submitBtn').prop('disabled', true);
            } else {
                displayError.textContent = '';
                $('#submitBtn').prop('disabled', false);
            }
        });

        $('input[name="payment_method"]').change(function() {
            if ($(this).val() === 'new') {
                $('#newCardSection').removeClass('d-none');
                if (!isCardMounted) {
                    cardElement.mount('#cardElement');
                    isCardMounted = true;
                }
            } else {
                $('#newCardSection').addClass('d-none');
                if (isCardMounted) {
                    cardElement.unmount();
                    isCardMounted = false;
                }
                $('#cardErrors').text('');
                $('#submitBtn').prop('disabled', false);
            }
        });

        $('#renewalForm').submit(async function(e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#submitBtn');

            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

            const formArray = form.serializeArray();
            const formData = {};
            formArray.forEach(({ name, value }) => formData[name] = value);

            try {
                if (formData.payment_method === 'new') {
                    const response = await fetch('/subscription/renewal/payment-intent', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            subscription_id: formData.subscription_id,
                            billing_cycle: formData.billing_cycle
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'Failed to create payment intent');
                    }

                    const { paymentIntentClientSecret } = await response.json();

                    if (!paymentIntentClientSecret) {
                        throw new Error('Payment Intent creation failed.');
                    }

                    const { error, paymentIntent } = await stripe.confirmCardPayment(paymentIntentClientSecret, {
                        payment_method: { card: cardElement }
                    });

                    if (error) {
                        $('#cardErrors').text(error.message);
                        throw error;
                    }

                    if (paymentIntent.status === 'succeeded') {
                        const confirmResponse = await fetch('/subscription/renewal/confirm', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                subscription_id: formData.subscription_id,
                                billing_cycle: formData.billing_cycle,
                                payment_intent_id: paymentIntent.id
                            })
                        });

                        if (!confirmResponse.ok) {
                            const errorData = await confirmResponse.json().catch(() => ({}));
                            throw new Error(errorData.message || 'Confirmation failed');
                        }

                        $('#successModal').modal('show');
                        form[0].reset();
                        if (isCardMounted) {
                            cardElement.clear();
                        }
                    }
                } else {
                    const response = await fetch('/subscription/renewal/saved-method', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            subscription_id: formData.subscription_id,
                            billing_cycle: formData.billing_cycle,
                            payment_method_id: formData.payment_method
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'Payment failed');
                    }

                    const result = await response.json();

                    if (result.success) {
                        $('#successModal').modal('show');
                        form[0].reset();
                        if (isCardMounted) {
                            cardElement.clear();
                        }
                    } else {
                        throw new Error(result.message || 'Renewal failed');
                    }
                }
            } catch (err) {
                alert('Error: ' + err.message);
                console.error(err);
            } finally {
                submitBtn.prop('disabled', false).html('<i class="fas fa-sync-alt me-2"></i> Renew Subscription');
            }
        });
    });
</script>
@endpush
