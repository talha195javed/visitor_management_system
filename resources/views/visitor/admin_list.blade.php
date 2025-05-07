@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table_list.css') }}">
<style>
    :root {
        --primary-color: #6c5ce7;
        --secondary-color: #a29bfe;
        --success-color: #00b894;
        --info-color: #0984e3;
        --warning-color: #fdcb6e;
        --danger-color: #d63031;
        --light-color: #f8f9fa;
        --dark-color: #2d3436;
        --border-radius: 8px;
        --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        --transition: all 0.25s cubic-bezier(0.645, 0.045, 0.355, 1);
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        background: white;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-container {
        padding: 0 1.5rem 1.5rem;
    }

    #visitorsTable {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    #visitorsTable thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border: none;
        text-transform: uppercase;
    }

    #visitorsTable tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        vertical-align: middle;
        transition: var(--transition);
        background: white;
    }

    #visitorsTable tbody tr:not(.no-hover):hover td {
        background-color: #f8f9fa;
        transform: translateX(4px);
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
        font-size: 0.75em;
        letter-spacing: 0.3px;
        border-radius: var(--border-radius);
    }

    .bg-primary { background-color: var(--primary-color) !important; }
    .bg-success { background-color: var(--success-color) !important; }
    .bg-warning { background-color: var(--warning-color) !important; color: #2d3436 !important; }
    .bg-danger { background-color: var(--danger-color) !important; }

    .btn-main {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .btn-main:hover {
        background-color: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 92, 231, 0.2);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        border-radius: var(--border-radius);
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .btn-outline:hover {
        background-color: rgba(108, 92, 231, 0.08);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .action-btns {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: var(--transition);
    }

    .btn-icon.view {
        background-color: rgba(9, 132, 227, 0.1);
        color: var(--info-color);
    }

    .btn-icon.view:hover {
        background-color: var(--info-color);
        color: white;
    }

    .btn-icon.archive {
        background-color: rgba(214, 48, 49, 0.1);
        color: var(--danger-color);
    }

    .btn-icon.archive:hover {
        background-color: var(--danger-color);
        color: white;
    }

    .visitor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(108, 92, 231, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: 600;
        margin-right: 12px;
    }

    .visitor-info {
        display: flex;
        align-items: center;
    }

    .visitor-name {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 2px;
    }

    .visitor-email {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .status-waiting { background-color: rgba(108, 92, 231, 0.1); color: var(--primary-color); }
    .status-waiting .dot { background-color: var(--primary-color); }

    .status-checkedin { background-color: rgba(0, 184, 148, 0.1); color: var(--success-color); }
    .status-checkedin .dot { background-color: var(--success-color); }

    .status-checkedout { background-color: rgba(253, 203, 110, 0.1); color: #d68910; }
    .status-checkedout .dot { background-color: #d68910; }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: none !important;
        padding: 0.5rem 0.9rem !important;
        margin: 0 0.15rem !important;
        border-radius: var(--border-radius) !important;
        transition: var(--transition) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--primary-color) !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa !important;
        color: var(--dark-color) !important;
    }

    .dataTables_filter input {
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: var(--border-radius) !important;
        padding: 0.5rem 1rem !important;
        transition: var(--transition) !important;
    }

    .dataTables_filter input:focus {
        outline: none !important;
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2) !important;
    }

    .empty-table-message {
        padding: 3rem;
        text-align: center;
        color: #6c757d;
    }

    .empty-table-message i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    @media (max-width: 767.98px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
        }

        .table-container {
            padding: 0;
        }

        #visitorsTable {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <br>
        <h1>Visitor Management</h1>
        <br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Visitors</li>
            </ol>
        </nav>
    </div>
    <br>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="m-0">Visitor Directory</h3>
                            <p class="text-muted small mb-0">Manage all visitor records and check-ins</p>
                        </div>
                        <a href="{{ route('visitors.admin_pre_register') }}" class="btn btn-main">
                            <i class="fas fa-plus me-2"></i>New Visitor
                        </a>
                    </div>

                    <div class="table-container">
                        <table id="visitorsTable" class="table" style="width:100%">
                            <thead>
                            <tr>
                                <th>Visitor</th>
                                <th>Contact</th>
                                <th>ID Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($visitors as $visitor)
                            <tr>
                                <td>
                                    <div class="visitor-info">
                                        <div class="visitor-avatar">
                                            {{ substr($visitor->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="visitor-name">{{ $visitor->full_name }}</div>
                                            <div class="visitor-email">{{ $visitor->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>{{ $visitor->phone ? '+'.$visitor->country_code.' '.$visitor->phone : 'N/A' }}</div>
                                        @if($visitor->email)
                                        <a href="mailto:{{ $visitor->email }}" class="text-primary small">{{ $visitor->email }}</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($visitor->identification_number)
                                    <span class="badge bg-light text-dark">{{ $visitor->identification_number }}</span>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($visitor->check_out_time == '' && $visitor->check_in_time == '')
                                    <span class="status-pill status-waiting">
                                            <span class="dot"></span>
                                            Waiting
                                        </span>
                                    @elseif ($visitor->check_out_time == '')
                                    <span class="status-pill status-checkedin">
                                            <span class="dot"></span>
                                            Checked In
                                        </span>
                                    @else
                                    <span class="status-pill status-checkedout">
                                            <span class="dot"></span>
                                            Checked Out
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('visitors.show', $visitor->id) }}" class="btn-icon view" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('visitors.archive', $visitor->id) }}" method="POST" class="d-inline-block archive-form">
                                            @csrf
                                            <button type="button" class="btn-icon archive archive-btn" data-bs-toggle="tooltip" title="Archive Visitor">
                                                <i class="fas fa-archive"></i>
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
</main>

<!-- Include jQuery, DataTables JS, Bootstrap JS, and SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        $('#visitorsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [[0, "asc"]],
            "language": {
                "emptyTable": "<div class='empty-table-message'><i class='fas fa-users-slash'></i><h5>No visitors found</h5><p>Add your first visitor to get started</p></div>",
                "info": "Showing _START_ to _END_ of _TOTAL_ visitors",
                "infoEmpty": "Showing 0 to 0 of 0 visitors",
                "infoFiltered": "(filtered from _MAX_ total)",
                "lengthMenu": "Show _MENU_",
                "search": "",
                "searchPlaceholder": "Search visitors...",
                "zeroRecords": "<div class='empty-table-message'><i class='fas fa-search'></i><h5>No matching visitors found</h5><p>Try different search criteria</p></div>",
                "paginate": {
                    "first": "«",
                    "last": "»",
                    "next": "›",
                    "previous": "‹"
                }
            },
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "drawCallback": function(settings) {
                // Add custom search input styling
                $('.dataTables_filter input').attr('placeholder', 'Search visitors...');
                $('.dataTables_filter label').contents().filter(function() {
                    return this.nodeType === 3;
                }).remove();
            },
            "columnDefs": [
                {
                    "targets": [4], // Actions column
                    "orderable": false,
                    "searchable": false,
                    "className": "text-end"
                }
            ]
        });

        // Enable Bootstrap tooltips
        $('[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

        // Archive confirmation
        $('.archive-btn').on('click', function (e) {
            e.preventDefault();
            let form = $(this).closest('form');
            let visitorName = $(this).closest('tr').find('.visitor-name').text();

            Swal.fire({
                title: 'Archive Visitor?',
                html: `You're about to archive <strong>${visitorName}</strong>. This action can be undone later.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary-color)',
                cancelButtonColor: 'var(--danger-color)',
                confirmButtonText: 'Yes, archive',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn-main',
                    cancelButton: 'btn-outline-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection
