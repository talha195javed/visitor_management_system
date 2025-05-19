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

    .card-profile {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-profile:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 1.5rem;
        border-bottom: none;
    }

    .btn-hover-light {
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-hover-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--accent-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }

    .badge-role {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 50rem;
    }

    .badge-superAdmin {
        background-color: #ff6b6b;
        color: white;
    }

    .badge-admin {
        background-color: #48cae4;
        color: white;
    }

    .badge-manager {
        background-color: #52b788;
        color: white;
    }

    .badge-client {
        background-color: #adb5bd;
        color: white;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .table-container {
        border-radius: 0 0 15px 15px;
        overflow: hidden;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(76, 201, 240, 0.05);
    }

    .pagetitle {
        margin-bottom: 2rem;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        padding: 5px 15px;
        border: 1px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 20px;
        padding: 5px;
        border: 1px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 50% !important;
        margin: 0 2px;
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white !important;
        border: none;
    }
</style>
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>User Management</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Register User
                </a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-profile animated fadeInUp">
                    <div class="card-header-gradient d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users me-3 text-white" style="font-size: 1.5rem;"></i>
                            <h3 class="m-0 text-white">User Directory</h3>
                        </div>
                        <div>
                            <span class="badge bg-light text-primary rounded-pill px-3 py-2">
                                Total Users: {{ count($users) }}
                            </span>
                        </div>
                    </div>

                    <div class="table-container">
                        <table id="userTable" class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                            <tr class="animated fadeInUp">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div>{{ $user->email }}</div>
                                        <small class="text-muted">Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($user->role == 'superAdmin')
                                    <span class="badge-role badge-superAdmin">Super Admin</span>
                                    @elseif($user->role == 'admin')
                                    <span class="badge-role badge-admin">Administrator</span>
                                    @elseif ($user->role == 'manager')
                                    <span class="badge-role badge-manager">Manager</span>
                                    @else
                                    <span class="badge-role badge-client">Client</span>
                                    @endif
                                </td>
                                <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Active
                                        </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.users.user_show', $user->id) }}"
                                           class="action-btn btn btn-sm btn-primary me-2"
                                           data-bs-toggle="tooltip"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('employees.archive', $user->id) }}" method="POST" class="archive-form">
                                            @csrf
                                            <button type="button"
                                                    class="action-btn btn btn-sm btn-danger archive-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Archive User">
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
    </section>
</main>

<script>
    $(document).ready(function() {
        // Initialize DataTable with enhanced options
        $('#userTable').DataTable({
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            "pagingType": "full_numbers",
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search users...",
                "lengthMenu": "Show _MENU_ users per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ users",
                "infoEmpty": "No users found",
                "infoFiltered": "(filtered from _MAX_ total users)",
                "paginate": {
                    "first": "<i class='fas fa-angle-double-left'></i>",
                    "last": "<i class='fas fa-angle-double-right'></i>",
                    "next": "<i class='fas fa-angle-right'></i>",
                    "previous": "<i class='fas fa-angle-left'></i>"
                }
            },
            "responsive": true,
            "columnDefs": [
                { "responsivePriority": 1, "targets": 0 }, // User column
                { "responsivePriority": 2, "targets": -1 }, // Actions column
                { "orderable": false, "targets": -1 } // Disable sorting for actions column
            ],
            "initComplete": function() {
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-select');
            }
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Archive confirmation
        $('.archive-btn').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Archive User',
                text: "Are you sure you want to archive this user? They will no longer have access to the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                backdrop: `
                    rgba(0,0,0,0.7)
                    url("{{ asset('images/trash-animation.gif') }}")
                    left top
                    no-repeat
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
