@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/subscriptions.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4cc9f0;
        --success-color: #4ade80;
        --warning-color: #fbbf24;
        --danger-color: #f87171;
        --dark-color: #1e293b;
        --light-color: #f8fafc;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    .main {
        background-color: #f5f7fb;
    }

    .pagetitle {
        padding: 20px 0;
    }

    .pagetitle h1 {
        font-weight: 600;
        color: var(--dark-color);
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    /* Stats Cards */
    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        z-index: -1;
    }

    .stat-card .card-title {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .stat-card h2 {
        font-weight: 600;
    }

    .stat-card i {
        transition: all 0.3s ease;
    }

    .stat-card:hover i {
        transform: scale(1.1);
    }

    /* Main Card */
    .main-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 1.5rem;
        border-bottom: none;
    }

    .card-header h3 {
        font-weight: 600;
        margin: 0;
    }

    /* Table Styles */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    #subscriptionsTable {
        border-collapse: separate;
        border-spacing: 0;
    }

    #subscriptionsTable thead th {
        background-color: var(--dark-color);
        color: white;
        font-weight: 500;
        padding: 1rem;
        border: none;
    }

    #subscriptionsTable tbody tr {
        transition: all 0.2s ease;
    }

    #subscriptionsTable tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateX(4px);
    }

    #subscriptionsTable tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Action Buttons */
    .btn-action {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-view {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border: 1px solid rgba(67, 97, 238, 0.3);
    }

    .btn-view:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Search Box */
    .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }

    .dataTables_filter input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        outline: none;
    }

    /* Pagination */
    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        margin: 0 0.2rem !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.2s ease !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }

    /* Badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-active {
        background-color: rgba(74, 222, 128, 0.1);
        color: var(--success-color);
    }

    .badge-pending {
        background-color: rgba(251, 191, 36, 0.1);
        color: var(--warning-color);
    }

    .badge-expired {
        background-color: rgba(248, 113, 113, 0.1);
        color: var(--danger-color);
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animated-row {
        animation: fadeIn 0.3s ease forwards;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-card i {
            font-size: 2rem !important;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Client Management</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active">Clients</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Client
                </button>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">TOTAL CLIENTS</h6>
                                <h2 class="mb-0">{{ $stats['total'] }}</h2>
                                <small class="opacity-75">+{{ $stats['recent'] }} this month</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">ACTIVE SUBSCRIPTIONS</h6>
                                <h2 class="mb-0">{{ $stats['active'] }}</h2>
                                <small class="opacity-75">{{ number_format(($stats['active']/$stats['total'])*100, 0) }}% of total</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">TOTAL REVENUE</h6>
                                <h2 class="mb-0">${{ number_format($stats['revenue'], 2) }}</h2>
                                <small class="opacity-75">${{ number_format($stats['revenue']/max(1, $stats['active']), 2) }} avg/client</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">NEW THIS MONTH</h6>
                                <h2 class="mb-0">{{ $stats['recent'] }}</h2>
                                <small class="opacity-75">{{ number_format(($stats['recent']/$stats['total'])*100, 0) }}% growth</small>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card main-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0 text-white">Client Directory</h3>
                        <div class="d-flex gap-2">
                            <div class="input-group" style="max-width: 250px;">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                                <input type="text" id="searchBox" class="form-control border-start-0" placeholder="Search clients...">
                            </div>
                            <button class="btn btn-light">
                                <i class="fas fa-filter me-1"></i> Filters
                            </button>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <!-- DataTable for subscriptions -->
                        <div class="table-responsive">
                            <table id="subscriptionsTable" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>CLIENT ID</th>
                                    <th>CLIENT NAME</th>
                                    <th>CONTACT INFO</th>
                                    <th>COMPANY</th>
                                    <th>JOIN DATE</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($clients as $client)
                                <tr class="animated-row" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                    <td><strong>#{{ $client->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                {{ strtoupper(substr($client->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $client->name }}</div>
                                                <small class="text-muted">Client since {{ \Carbon\Carbon::parse($client->created_at)->format('M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $client->email }}</div>
                                        <small class="text-muted">{{ $client->phone ?? 'No phone' }}</small>
                                    </td>
                                    <td>
                                        @if($client->company)
                                        <span class="badge bg-light text-dark">{{ $client->company }}</span>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($client->created_at)->format('d M Y') }}
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($client->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        @php
                                        $status = 'active'; // You would determine this based on your business logic
                                        $badgeClass = $status === 'active' ? 'badge-active' : ($status === 'pending' ? 'badge-pending' : 'badge-expired');
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.client_subscriptions.show', $client->id) }}"
                                               class="btn-action btn-view" title="View Subscriptions">
                                                View<br> Details
                                            </a>
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
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

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
            "dom": '<"top"<"d-flex justify-content-between align-items-center"lfB>>rt<"bottom"ip><"clear">',
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel me-2"></i>Export',
                    className: 'btn btn-sm btn-success'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print me-2"></i>Print',
                    className: 'btn btn-sm btn-light'
                }
            ],
            "language": {
                "search": "",
                "searchPlaceholder": "Search clients...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ clients",
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            },
            "initComplete": function () {
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-select');
                $('.dt-buttons button').removeClass('dt-button');
            },
            "drawCallback": function (settings) {
                // Add animation to newly drawn rows
                $('tbody tr', this.api().table().container()).each(function(i) {
                    $(this).css('opacity', 0).delay(i * 50).animate({'opacity': 1}, 100);
                });
            }
        });

        // Focus search box when / is pressed (like Slack/Gmail)
        $(document).keyup(function(e) {
            if (e.key === '/') {
                $('#searchBox').focus();
            }
        });
    });
</script>
@endpush

@endsection
