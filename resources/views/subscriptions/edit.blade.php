@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Edit Subscription</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a></li>
                        <li class="breadcrumb-item active">Edit #{{ $subscription->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="subscription-badge">
                <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : ($subscription->status == 'offer_time' ? 'warning' : ($subscription->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                    {{ ucfirst($subscription->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="subscription-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="m-0">
                                <i class="fas fa-edit me-2"></i>Edit Subscription
                            </h2>
                            <div class="subscription-id">
                                #{{ $subscription->id }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.subscriptions.update', $subscription->id) }}" class="subscription-form">
                            @csrf
                            @method('PUT')

                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="fas fa-user-circle me-2"></i>Customer Information
                                </h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                                   value="{{ old('customer_name', $subscription->customer_name) }}" readonly>
                                            <label for="customer_name">Customer Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="package_type" name="package_type" disabled>
                                                <option value="basic" {{ old('package_type', $subscription->package_type) == 'basic' ? 'selected' : '' }}>Basic</option>
                                                <option value="professional" {{ old('package_type', $subscription->package_type) == 'professional' ? 'selected' : '' }}>Professional</option>
                                                <option value="enterprise" {{ old('package_type', $subscription->package_type) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                                            </select>
                                            <label for="package_type">Package Type</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="fas fa-money-bill-wave me-2"></i>Payment Details
                                </h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">AED</span>
                                                <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                                       value="{{ old('amount', $subscription->amount) }}" readonly>
                                                <label for="amount">Amount</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="status" name="status" required @if(Auth::user()->role == 'client') disabled @endif>
                                                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="pending" {{ $subscription->status == 'offer_time' ? 'selected' : '' }}>Bonous Time</option>
                                                <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                            </select>
                                            <label for="status">Status</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="fas fa-calendar-alt me-2"></i>Subscription Period
                                </h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                   value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required
                                                   @if(Auth::user()->role == 'client') disabled @endif>
                                            <label for="start_date">Start Date</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                   value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required
                                                   @if(Auth::user()->role == 'client') disabled @endif>
                                            <label for="end_date">End Date</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="fas fa-sync-alt me-2"></i>Subscription Management
                                </h4>
                                <div class="d-flex justify-content-between align-items-center">

                                    @if(Auth::user()->role == 'superAdmin')
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="auto_renew" value="0">
                                        <input class="form-check-input" type="checkbox" id="auto_renew" name="auto_renew" value="1"
                                               {{ old('auto_renew', $subscription->auto_renew) ? 'checked' : '' }}
                                        <label class="form-check-label" for="auto_renew">Auto Renewal</label>
                                    </div>

                                    @elseif(Auth::user()->role == 'client')

                                    <button type="button" class="btn btn-cancel-subscription" data-bs-toggle="modal" data-bs-target="#cancelSubscriptionModal">
                                        <i class="fas fa-ban me-2"></i> Cancel Subscription
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="form-actions">
                                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary-gradient">
                                    <i class="fas fa-save me-2"></i> Update Subscription
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelSubscriptionModal" tabindex="-1" aria-labelledby="cancelSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelSubscriptionModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Cancel Subscription Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="cancel-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <h4 class="mt-3">Need to cancel your subscription?</h4>
                    <p class="text-muted">To cancel your subscription, please contact our support team directly. We're here to help!</p>
                </div>

                <div class="contact-options">
                    <div class="contact-option email-option">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Email Support</h5>
                            <p>Send us an email with your cancellation request</p>
                            <a href="mailto:support@smartvisitor.com?subject=Subscription Cancellation Request" class="btn btn-outline-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i> Email Us
                            </a>
                        </div>
                    </div>

                    <div class="contact-option whatsapp-option mt-3">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-details">
                            <h5>WhatsApp Support</h5>
                            <p>Chat with us directly on WhatsApp</p>
                            <a href="https://wa.me/971504406565?text=I%20would%20like%20to%20cancel%20my%20subscription" class="btn btn-outline-success w-100">
                                <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --danger-color: #f72585;
        --warning-color: #f8961e;
        --light-color: #f8f9fa;
        --dark-color: #212529;
    }

    .subscription-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .subscription-card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .card-header h2 {
        font-weight: 600;
        font-size: 1.5rem;
    }

    .subscription-id {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }

    .form-floating label {
        color: #6c757d;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 1rem 1rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        color: white;
    }

    .btn-primary-gradient:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }

    .subscription-badge .badge {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Cancel Subscription Button Styles */
    .btn-cancel-subscription {
        background: linear-gradient(135deg, #f72585, #b5179e);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(247, 37, 133, 0.3);
        display: flex;
        align-items: center;
    }

    .btn-cancel-subscription:hover {
        background: linear-gradient(135deg, #b5179e, #f72585);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(247, 37, 133, 0.4);
        color: white;
    }

    /* Modal Styles */
    .cancel-icon {
        font-size: 4rem;
        color: #f72585;
        margin-bottom: 1rem;
    }

    .contact-options {
        margin-top: 2rem;
    }

    .contact-option {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .contact-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
        padding: 0.75rem;
        border-radius: 50%;
        background-color: white;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .email-option .contact-icon {
        color: #4361ee;
        border: 1px solid #4361ee;
    }

    .whatsapp-option .contact-icon {
        color: #25D366;
        border: 1px solid #25D366;
    }

    .contact-details {
        flex: 1;
    }

    .contact-details h5 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .contact-details p {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .form-actions .btn {
            width: 100%;
        }

        .contact-option {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .contact-icon {
            margin-right: 0;
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // You can add any additional JavaScript here if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Any initialization code can go here
    });
</script>
@endpush
