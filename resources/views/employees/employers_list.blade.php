@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table_list.css') }}">
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4cc9f0;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --success-color: #4bb543;
    }

    .employee-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .employee-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 1.5rem;
        border-bottom: none;
    }

    .table-container {
        padding: 0 1.5rem 1.5rem;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background-color: #f1f3ff;
        color: var(--dark-color);
    }

    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
    }

    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #f1f3ff;
    }

    .table tr:hover td {
        background-color: #f8f9ff;
    }

    .btn-hover {
        transition: all 0.3s ease;
        border-radius: 6px;
        font-weight: 500;
    }

    .btn-light {
        background-color: white;
        color: var(--primary-color);
    }

    .btn-light:hover {
        background-color: #f1f3ff;
        color: var(--secondary-color);
    }

    .action-btns .btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin: 0 3px;
    }

    .employee-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--accent-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }

    .employee-name {
        display: flex;
        align-items: center;
    }

    .badge-position {
        background-color: #e0f7fa;
        color: #00838f;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
    }

    .search-container {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .search-container i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .search-input {
        padding-left: 40px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        height: 45px;
    }

    .dataTables_filter, .dataTables_length {
        display: none;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .pagination .page-link {
        color: var(--primary-color);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .card-header-gradient {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-header-gradient h3 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Employee Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card employee-card">
                    <div class="card-header-gradient d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="m-0 text-white">Employee Directory</h3>
                            <p class="m-0 text-white-50" style="font-size: 0.875rem;">Manage your organization's workforce</p>
                        </div>
                        <a href="{{ route('create_employee') }}" class="btn btn-light btn-hover">
                            <i class="fas fa-plus me-2"></i>Add Employee
                        </a>
                    </div>

                    <div class="table-container">
                        <div class="search-container">
                            <i class="fas fa-search"></i>
                            <input type="text" id="customSearch" class="form-control search-input" placeholder="Search employees...">
                        </div>

                        <div class="table-responsive">
                            <table id="employeeTable" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Contact</th>
                                    <th>Company</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($employees as $employee)
                                <tr>
                                    <td>
                                        <div class="employee-name">
                                            <div class="employee-avatar">
                                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $employee->name }}</strong><br>
                                                <small class="text-muted">{{ $employee->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->contact_number ?: 'N/A' }}</td>
                                    <td>{{ $employee->company ?: 'N/A' }}</td>
                                    <td>
                                        <span class="badge-position">{{ $employee->position ?: 'Not specified' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td class="action-btns">
                                        <a href="{{ route('employee_show', $employee->id) }}"
                                           class="btn btn-primary btn-sm btn-hover"
                                           data-bs-toggle="tooltip"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('employees.archive', $employee->id) }}"
                                              method="POST"
                                              class="d-inline-block archive-form">
                                            @csrf
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-hover archive-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Archive Employee">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-users-slash"></i>
                                            <h4>No Employees Found</h4>
                                            <p>Get started by adding your first employee</p>
                                            <a href="{{ route('create_employee') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-2"></i>Add Employee
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        var table = $('#employeeTable').DataTable({
            "dom": '<"top"f>rt<"bottom"lip><"clear">',
            "language": {
                "search": "",
                "searchPlaceholder": "Search employees...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ employees",
                "infoEmpty": "No employees found",
                "infoFiltered": "(filtered from _MAX_ total employees)",
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            },
            "responsive": true,
            "columnDefs": [
                { "responsivePriority": 1, "targets": 0 },
                { "responsivePriority": 2, "targets": -1 }
            ]
        });

        // Custom search input
        $('#customSearch').keyup(function(){
            table.search($(this).val()).draw();
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Archive confirmation
        $('.archive-btn').on('click', function (e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Archive Employee?',
                text: "This employee will be moved to archives. You can restore them later.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Archive',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
