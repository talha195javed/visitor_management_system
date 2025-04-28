@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Subscription</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscriptions</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card animated fadeInUp">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Edit Subscription #{{ $subscription->id }}</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.subscriptions.update', $subscription->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                           value="{{ old('customer_name', $subscription->customer_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="package_type" class="form-label">Package Type</label>
                                    <select class="form-control" id="package_type" name="package_type" required>
                                        <option value="basic" {{ old('package_type', $subscription->package_type) == 'basic' ? 'selected' : '' }}>Basic</option>
                                        <option value="professional" {{ old('package_type', $subscription->package_type) == 'professional' ? 'selected' : '' }}>Professional</option>
                                        <option value="enterprise" {{ old('package_type', $subscription->package_type) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                               value="{{ old('amount', $subscription->amount) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ $subscription->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                           value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Subscription
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
