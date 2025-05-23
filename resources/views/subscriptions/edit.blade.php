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
                                            <select class="form-select" id="status" name="status" required>
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
                                                   value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required>
                                            <label for="start_date">Start Date</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                   value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required>
                                            <label for="end_date">End Date</label>
                                        </div>
                                    </div>
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

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush
