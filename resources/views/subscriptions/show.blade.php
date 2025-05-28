@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Subscription Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/subscriptions/index">Subscriptions</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card animated fadeInUp">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Subscription #{{ $subscription->id }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Customer Information</h5>
                                <p><strong>Name:</strong> {{ $subscription->customer_name }}</p>
                                <p><strong>Customer ID:</strong> {{ $subscription->customer_id ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Subscription Details</h5>
                                <p><strong>Status:</strong>
                                    <span class="badge
                                        @if($subscription->status == 'active') bg-success
                                        @elseif($subscription->status == 'renewed') bg-secondary
                                        @elseif($subscription->status == 'cancelled') bg-warning
                                        @elseif($subscription->status == 'expired') bg-danger
                                        @endif">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </p>
                                <p><strong>Plan:</strong> {{ $subscription->package_type }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Billing Information</h5>
                                <p><strong>Amount:</strong> ${{ number_format($subscription->amount, 2) }}</p>
                                <p><strong>Billing Cycle:</strong>
                                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('M j, Y') }}
                                    to
                                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Dates</h5>
                                <p><strong>Created:</strong> {{ $subscription->created_at->format('M j, Y H:i') }}</p>
                                <p><strong>Last Updated:</strong> {{ $subscription->updated_at->format('M j, Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            @if(Auth::user()->role == 'superAdmin')
                            <a href="/subscriptions/index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                            @elseif(Auth::user()->role == 'client')
                            <a href="/client_subscriptions/index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to My Subscriptions
                            </a>
                            @endif
                            <div>
                                @if( !(Auth::user()->role == 'client' && in_array($subscription->status, ['renewed', 'expired', 'cancelled'])) )
                                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-primary me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this subscription?')">
                                        <i class="fas fa-ban me-1"></i> Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
