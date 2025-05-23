@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/subscriptions.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')

<main id="main" class="main">
    <!-- Gradient Header -->
    <div class="pagetitle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem 0; border-radius: 0 0 15px 15px;">
        <div class="container">
            <h1 style="color: white; font-weight: 700; text-shadow: 1px 1px 3px rgba(0,0,0,0.2);">Subscription Management</h1>
            <nav>
                <ol class="breadcrumb" style="background-color: rgba(255,255,255,0.2); border-radius: 20px; padding: 0.5rem 1rem; display: inline-block;">
                    <li class="breadcrumb-item"><a href="/home" style="color: white;">Home</a></li>
                    <li class="breadcrumb-item active" style="color: white;">Client Subscriptions</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mt-5">
        <!-- Modern Stats Cards with Icons -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="card stat-card border-0 shadow-sm" style="border-left: 4px solid #4e73df;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">TOTAL SUBSCRIPTIONS</h6>
                                <h2 class="mb-0" style="color: #4e73df; font-weight: 700;">{{ $stats['total'] }}</h2>
                            </div>
                            <div class="icon-circle" style="background-color: rgba(78, 115, 223, 0.1); color: #4e73df;">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-success"><i class="fas fa-caret-up"></i> 12.5%</span>
                            <span class="text-muted ml-2">Since last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card stat-card border-0 shadow-sm" style="border-left: 4px solid #1cc88a;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">ACTIVE</h6>
                                <h2 class="mb-0" style="color: #1cc88a; font-weight: 700;">{{ $stats['active'] }}</h2>
                            </div>
                            <div class="icon-circle" style="background-color: rgba(28, 200, 138, 0.1); color: #1cc88a;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-success"><i class="fas fa-caret-up"></i> 8.3%</span>
                            <span class="text-muted ml-2">Since last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card stat-card border-0 shadow-sm" style="border-left: 4px solid #36b9cc;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">TOTAL EXPENSE</h6>
                                <h2 class="mb-0" style="color: #36b9cc; font-weight: 700;">${{ number_format($stats['revenue'], 2) }}</h2>
                            </div>
                            <div class="icon-circle" style="background-color: rgba(54, 185, 204, 0.1); color: #36b9cc;">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-success"><i class="fas fa-caret-up"></i> 22.1%</span>
                            <span class="text-muted ml-2">Since last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card stat-card border-0 shadow-sm" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">NEW (30 DAYS)</h6>
                                <h2 class="mb-0" style="color: #f6c23e; font-weight: 700;">{{ $stats['recent'] }}</h2>
                            </div>
                            <div class="icon-circle" style="background-color: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-danger"><i class="fas fa-caret-down"></i> 3.5%</span>
                            <span class="text-muted ml-2">Since last month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card with Gradient Header -->
        <div class="card shadow-lg border-0 overflow-hidden">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="m-0 text-white" style="font-weight: 600;">Client Subscriptions</h3>
                    <div class="d-flex">
                        <button class="btn btn-light btn-sm me-2">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <button class="btn btn-light btn-sm">
                            <i class="fas fa-filter me-1"></i> Filters
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- DataTable for subscriptions -->
                <div class="table-responsive">
                    <table id="subscriptionsTable" class="table table-hover align-middle mb-0" cellspacing="0" width="100%">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $subscription->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        <span>{{ strtoupper(substr($subscription->customer_name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $subscription->customer_name }}</div>
                                        <small class="text-muted">Customer ID: {{ $subscription->customer_id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $subscription->customer_email }}</td>
                            <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $subscription->package_type ?? 'N/A' }}
                                    </span>
                            </td>
                            <td class="fw-bold">${{ number_format($subscription->amount, 2) }}</td>
                            <td>
                                    <span class="badge rounded-pill py-1 px-3
                                        @if($subscription->status == 'active') bg-success-light text-success
                                        @elseif($subscription->status == 'pending') bg-warning-light text-warning
                                        @elseif($subscription->status == 'cancelled') bg-secondary-light text-secondary
                                        @elseif($subscription->status == 'expired') bg-danger-light text-danger
                                        @endif">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}"
                                       class="btn btn-sm btn-light rounded-start"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}"
                                       class="btn btn-sm btn-light"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light rounded-end"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel"
                                                onclick="return confirm('Are you sure you want to cancel this subscription?')">
                                            <i class="fas fa-ban text-danger"></i>
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

            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing <span class="fw-bold">1</span> to <span class="fw-bold">10</span> of <span class="fw-bold">{{ $stats['total'] }}</span> entries
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- Bootstrap Tooltips -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize DataTable
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
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            },
            "drawCallback": function (settings) {
                // Reinitialize tooltips after table redraw
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            }
        });
    });
</script>
@endpush

<style>
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --success-light: rgba(28, 200, 138, 0.1);
        --warning-light: rgba(246, 194, 62, 0.1);
        --danger-light: rgba(231, 74, 59, 0.1);
        --secondary-light: rgba(108, 117, 125, 0.1);
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #4e73df;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .bg-success-light {
        background-color: var(--success-light);
    }

    .bg-warning-light {
        background-color: var(--warning-light);
    }

    .bg-danger-light {
        background-color: var(--danger-light);
    }

    .bg-secondary-light {
        background-color: var(--secondary-light);
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .table th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #6c757d;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .pagination .page-link {
        color: var(--primary);
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        padding-left: 15px;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 20px;
        padding: 0.25rem 1.5rem 0.25rem 0.75rem;
    }
</style>
@endsection
