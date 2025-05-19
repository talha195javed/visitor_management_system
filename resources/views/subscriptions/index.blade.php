@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/subscriptions.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Subscription Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Subscriptions</li>
            </ol>
        </nav>
    </div>

    <div class="container mt-4">
        <!-- Stats Cards -->
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
                <div class="card animated fadeInUp">
                    <div
                        class="card-header d-flex justify-content-between align-items-center bg-primary text-white mb-4">
                        <h3 class="m-0">All Subscriptions</h3>
                        <div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- DataTable for subscriptions -->
                        <div class="table-responsive">
                            <table id="subscriptionsTable" class="table table-hover table-bordered" cellspacing="0"
                                   width="100%">
                                <thead class="table-dark">
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
                                @foreach($subscriptions as $subscription)
                                <tr class="animated fadeInUp">
                                    <td>{{ $subscription->id }}</td>
                                    <td>
                                        {{ $subscription->customer_name }}
                                    </td>
                                    <td>{{ $subscription->customer_email }}</td>
                                    <td>{{ $subscription->package_type ?? 'N/A' }}</td>
                                    <td>${{ number_format($subscription->amount, 2) }}</td>
                                    <td>
                                            <span class="badge
                                                @if($subscription->status == 'active') bg-success
                                                @elseif($subscription->status == 'pending') bg-warning
                                                @elseif($subscription->status == 'cancelled') bg-secondary
                                                @elseif($subscription->status == 'expired') bg-danger
                                                @endif">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
<!--                                            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}"-->
<!--                                               class="btn btn-sm btn-outline-warning" title="Edit">-->
<!--                                                <i class="fas fa-edit"></i>-->
<!--                                            </a>-->
                                            <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}"
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Cancel"
                                                        onclick="return confirm('Are you sure you want to cancel this subscription?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#subscriptionsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": '<"top"lf>rt<"bottom"ip><"clear">',
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search subscriptions...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            },
            "initComplete": function () {
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-select');
            },

            "drawCallback": function (settings) {
                // Attach click handlers to delete buttons
                $('.btn-outline-danger').click(function (e) {
                    if (!confirm('Are you sure you want to cancel this subscription?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    });
</script>
@endpush

@endsection
