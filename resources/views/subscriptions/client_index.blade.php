@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/client_index.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endpush

@section('content')

<main id="main" class="main subscription-management">
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Subscription Management</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Subscriptions</li>
                    </ol>
                </nav>
            </div>
            @if($activeSubscriptions->count())
            <a href="{{ route('admin.packages.index') }}" class="subscription-btn">
                <i class="fas fa-plus-circle me-2"></i> Select New Package
            </a>
            @else
            <a href="{{ route('admin.packages.index') }}" class="subscription-btn">
                <i class="fas fa-arrow-up me-2"></i> Upgrade Your Package
            </a>
            @endif
        </div>
    </div>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">TOTAL SUBSCRIPTIONS</h6>
                                <h2 class="mb-0">{{ $stats['total'] }}</h2>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">ACTIVE</h6>
                                <h2 class="mb-0">{{ $stats['active'] }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">TOTAL REVENUE</h6>
                                <h2 class="mb-0">${{ number_format($stats['revenue'], 2) }}</h2>
                            </div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">NEW (30 DAYS)</h6>
                                <h2 class="mb-0">{{ $stats['recent'] }}</h2>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header card-header-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="m-0"><i class="fas fa-bolt me-2"></i> Active Subscriptions</h4>
                            <span class="badge bg-white text-primary">{{ $activeSubscriptions->count() }} Active</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($activeSubscriptions->count())
                        <div class="table-responsive">
                            <table class="table table-hover" id="activeTable">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days Left</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activeSubscriptions as $subscription)
                                @php
                                $endDate = \Carbon\Carbon::parse($subscription->end_date);
                                $today = \Carbon\Carbon::today();
                                $daysLeft = $today->diffInDays($endDate, false);

                                $statusLabel = '';
                                $badgeClass = '';
                                $statusIndicator = '';

                                if ($endDate->isPast()) {
                                $statusLabel = 'Expired';
                                $badgeClass = 'badge-expired';
                                $statusIndicator = 'status-expired';
                                } elseif ($daysLeft <= 7) {
                                $statusLabel = 'Expiring Soon';
                                $badgeClass = 'badge-expiring';
                                $statusIndicator = 'status-expiring';
                                } else {
                                $statusLabel = 'Active';
                                $badgeClass = 'badge-active';
                                $statusIndicator = 'status-active';
                                }
                                @endphp
                                <tr>
                                    <td>#{{ $subscription->id }}</td>
                                    <td>{{ $subscription->customer_name }}</td>
                                    <td>{{ $subscription->customer_email }}</td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary">
                                            {{ $subscription->package_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($subscription->amount, 2) }}</td>
                                    <td>
                                        <span class="{{ $statusIndicator }}"></span>
                                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</td>
                                    <td>{{ $endDate->format('d M Y') }}</td>
                                    <td>
                                        @if($daysLeft > 0)
                                        <span class="text-success">{{ $daysLeft }} days</span>
                                        @else
                                        <span class="text-danger">Expired</span>
                                        @endif
                                    </td>
                                    <td class="table-actions">
                                        <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.packages.index') }}"
                                           class="btn btn-sm btn-outline-success" title="Upgrade">
                                            <i class="fas fa-arrow-up"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">No active subscriptions found</h5>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus-circle me-2"></i> Subscribe to a Plan
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header card-header-secondary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="m-0"><i class="fas fa-history me-2"></i> Previous Subscriptions</h4>
                            <span class="badge bg-white text-primary">{{ $expiredSubscriptions->count() }} Records</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($expiredSubscriptions->count())
                        <div class="table-responsive">
                            <table class="table table-hover" id="expiredTable">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($expiredSubscriptions as $subscription)
                                @php
                                $endDate = \Carbon\Carbon::parse($subscription->end_date);
                                $today = \Carbon\Carbon::today();
                                $statusLabel = '';
                                $badgeClass = '';
                                $statusIndicator = '';

                                if ($endDate->isPast()) {
                                $statusLabel = 'Expired';
                                $badgeClass = 'badge-expired';
                                $statusIndicator = 'status-expired';
                                } else {
                                $statusLabel = 'Active';
                                $badgeClass = 'badge-active';
                                $statusIndicator = 'status-active';
                                }
                                @endphp
                                <tr>
                                    <td>#{{ $subscription->id }}</td>
                                    <td>{{ $subscription->customer_name }}</td>
                                    <td>{{ $subscription->customer_email }}</td>
                                    <td>
                                        <span class="badge bg-soft-secondary text-secondary">
                                            {{ $subscription->package_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($subscription->amount, 2) }}</td>
                                    <td>
                                        <span class="{{ $statusIndicator }}"></span>
                                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</td>
                                    <td>{{ $endDate->format('d M Y') }}</td>
                                    <td class="table-actions">
                                        <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($activeSubscriptions->count() === 0)
                                        <a href="{{ route('admin.renew_packages.index', ['id' => $subscription->id]) }}"
                                           class="btn btn-sm btn-outline-success" title="Renew">
                                            <i class="fas fa-redo-alt"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">No previous subscriptions found</h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="{{ asset('js/subscriptions.js') }}"></script>

@endpush

@endsection
