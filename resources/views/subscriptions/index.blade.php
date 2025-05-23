@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/subscriptions.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        --success-gradient: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
        --info-gradient: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
        --warning-gradient: linear-gradient(135deg, #f8961e 0%, #f3722c 100%);
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.1;
        z-index: -1;
    }

    .stat-card.bg-primary::before {
        background: var(--primary-gradient);
    }

    .stat-card.bg-success::before {
        background: var(--success-gradient);
    }

    .stat-card.bg-info::before {
        background: var(--info-gradient);
    }

    .stat-card.bg-warning::before {
        background: var(--warning-gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        background: var(--primary-gradient) !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-responsive {
        border-radius: 0 0 12px 12px;
        overflow: hidden;
    }

    #subscriptionsTable {
        border-collapse: separate;
        border-spacing: 0;
    }

    #subscriptionsTable thead th {
        background: #2b2d42;
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px 10px;
    }

    #subscriptionsTable tbody tr {
        transition: all 0.2s ease;
    }

    #subscriptionsTable tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.005);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    #subscriptionsTable tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .badge {
        padding: 6px 10px;
        font-weight: 500;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .btn-group .btn {
        border-radius: 8px !important;
        margin-right: 5px;
        transition: all 0.2s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-primary {
        border-color: #4361ee;
        color: #4361ee;
    }

    .btn-outline-primary:hover {
        background: #4361ee;
        color: white;
    }

    .btn-outline-danger {
        border-color: #f72585;
        color: #f72585;
    }

    .btn-outline-danger:hover {
        background: #f72585;
        color: white;
    }

    .pagetitle {
        position: relative;
    }

    .pagetitle::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        padding: 5px 10px;
        border: 1px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        padding: 5px;
        border: 1px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        margin: 0 3px;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--primary-gradient) !important;
        color: white !important;
        border: 1px solid transparent !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animated-table-row {
        animation: fadeIn 0.5s ease forwards;
    }

    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-active { background-color: #4cc9f0; }
    .status-pending { background-color: #f8961e; }
    .status-cancelled { background-color: #6c757d; }
    .status-expired { background-color: #f72585; }

    .customer-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-weight: bold;
        font-size: 14px;
    }
</style>
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0 text-white">All Subscriptions</h3>
                        <div>
                            <button class="btn btn-light">
                                <i class="fas fa-download me-2"></i>Export
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- DataTable for subscriptions -->
                        <div class="table-responsive">
                            <table id="subscriptionsTable" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
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
                                <tr class="animated-table-row" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                    <td>#{{ $subscription->id }}</td>
                                    <td>
                                        <span class="customer-avatar">{{ strtoupper(substr($subscription->customer_name, 0, 1)) }}</span>
                                        {{ $subscription->customer_name }}
                                    </td>
                                    <td>{{ $subscription->customer_email }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $subscription->package_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td><strong>AED {{ number_format($subscription->amount, 2) }}</strong></td>
                                    <td>
                                        <span class="status-indicator status-{{ $subscription->status }}"></span>
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
                                            <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}"
                                                  method="POST" style="display: inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Cancel">
                                                    <i class="fas fa-trash-alt"></i>
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

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        const table = $('#subscriptionsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": '<"top"<"d-flex justify-content-between align-items-center"lf>>rt<"bottom"ip><"clear">',
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
            }
        });

        // Add custom header
        $('.dataTables_wrapper .dataTables_filter').prepend(
            '<div class="me-3">' +
            '<select class="form-select form-select-sm status-filter">' +
            '<option value="">All Statuses</option>' +
            '<option value="active">Active</option>' +
            '<option value="pending">Pending</option>' +
            '<option value="cancelled">Cancelled</option>' +
            '<option value="expired">Expired</option>' +
            '</select>' +
            '</div>'
        );

        // Status filter functionality
        $('.status-filter').on('change', function() {
            table.column(5).search(this.value).draw();
        });

        // Delete confirmation with SweetAlert
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            const subscriptionId = $(this).closest('tr').find('td:first').text();

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to cancel subscription ${subscriptionId}. This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it',
                backdrop: `
                    rgba(0,0,123,0.4)
                    url("/images/nyan-cat.gif")
                    left top
                    no-repeat
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            Swal.fire(
                                'Cancelled!',
                                `Subscription ${subscriptionId} has been cancelled.`,
                                'success'
                            ).then(() => {
                                table.row($(form).closest('tr')).remove().draw();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'There was a problem cancelling the subscription.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

@endsection
