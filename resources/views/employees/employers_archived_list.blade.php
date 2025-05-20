@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table_list.css') }}">
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Archived Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Employees</li>
                <li class="breadcrumb-item active">Archived</li>
            </ol>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card animated fadeInUp">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h3 class="m-0">Archived Employees List</h3>
                        <a href="/employers_list" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Active Employees
                        </a>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="table-responsive">
                            <table id="archivedEmployeesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Position</th>
                                    <th>Contact #</th>
                                    <th>Archived Date</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($archivedEmployees as $employee)
                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->company }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->contact_number }}</td>
                                    <td>{{ $employee->deleted_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <form action="{{ route('employers_restore', $employee->id) }}" method="POST" class="me-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-success btn-sm restore-btn" title="Restore Employee">
                                                    <i class="fas fa-trash-restore"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No archived employees found</td>
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

<!-- Permanent Delete Modal -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-labelledby="permanentDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="permanentDeleteModalLabel">Confirm Permanent Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="permanentDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>You are about to permanently delete <strong id="employeeName"></strong>. This action cannot be undone!</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> All related data will be permanently removed from the system.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with responsive features
        $('#archivedEmployeesTable').DataTable({
            responsive: true,
            order: [[5, 'desc']], // Sort by archived date by default
            columnDefs: [
                { responsivePriority: 1, targets: 0 }, // Name
                { responsivePriority: 2, targets: -1 }, // Actions
                { targets: [5], render: DataTable.render.datetime('MMM D, YYYY h:mm A') }
            ]
        });

        // Restore confirmation
        $('.restore-btn').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Restore Employee',
                text: 'Are you sure you want to restore this employee?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush
