@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table_list.css') }}">
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Visitors Archived List</li>
            </ol>
        </nav>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="">
<br><br>
                <div class="card animated fadeInUp">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h3 class="m-0">Visitors Archived List</h3>
                    </div>

                    <!-- DataTable for visitor list -->
                    <div class="table-container">
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Identification Number</th>
                                <th>Contact #</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Loop through visitors (PHP) -->
                            @foreach($archivedVisitors as $visitor)
                            <tr class="animated fadeInUp">
                                <td>{{ $visitor->full_name }}</td>
                                <td>{{ $visitor->email }}</td>
                                <td>{{ $visitor->identification_number }}</td>
                                <td>{{ $visitor->phone }}</td>
                                <td>
                                            <span class="badge bg-warning">
                                               {{ 'Archived' }}
                                            </span>
                                </td>
                                <td>
                                    <form action="{{ route('visitors_restore', $visitor->id) }}" method="POST" class="d-inline-block restore-form">
                                        @csrf
                                        <button type="button" class="btn btn-danger btn-sm btn-hover restore-btn">
                                            <i class="fas fa-trash-restore"> <span>Restore</span></i>
                                        </button>
                                    </form>
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Initialize DataTable -->
<script>

    $(document).ready(function() {
        $('#example').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                {
                    "targets": [5], // Example: Hiding action column in smaller screens
                    "visible": true,
                    "responsivePriority": 1
                }
            ]
        });

        // SweetAlert2 confirmation on delete
        $('.fa-trash').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff8a00',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform delete action here
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                }
            });
        });
    });

</script>

@endsection

