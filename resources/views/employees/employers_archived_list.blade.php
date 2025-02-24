@extends('layouts.app')

@section('content')

<style>

    table thead th {
        background: linear-gradient(45deg, #989188, #e4e2db);
        color: #fff;
        text-align: center;
        font-weight: bold;
    }

    table tbody tr {
        background-color: #fff;
        transition: all 0.3s ease-in-out;
    }

    /* Fancy hover effect */
    table tbody tr:hover {
        transform: scale(1.05);
        background-color: #fffbf0;
        box-shadow: 0 4px 20px rgba(255, 138, 0, 0.3);
    }

    table tbody tr:nth-child(odd) {
        background-color: #fafafa;
    }

    table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 50px;
        padding-left: 20px;
        border: 2px solid #ff8a00;
        background-color: #fef9e4;
        margin-right: 20px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #ffbc00;
        box-shadow: 0 0 8px rgba(255, 138, 0, 0.5);
    }

    /* Pagination buttons with rounded corners and hover effects */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: #ffffff;
        color: black;
        font-size: 16px;
        padding: 8px 15px;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #b5b1a8;
        transform: scale(1.1);
    }

    /* Action buttons with hover effects */
    .action-buttons i {
        font-size: 18px;
        padding: 8px;
        background: #ff8a00;
        color: #fff;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-buttons i:hover {
        background: #ffbc00;
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(255, 138, 0, 0.4);
    }

    /* Gradient border effect */
    .table-container {
        padding: 30px;
        border-radius: 15px;
        border: 5px solid transparent;
        background: white;
        -webkit-background-clip: padding-box;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    @media (max-width: 767px) {
        table thead th {
            font-size: 12px;
        }

        table tbody td {
            font-size: 10px;
        }
    }

    #example_paginate {
        padding-top: 1% !important;
        text-align: end !important;
    }
    #example_length {
        text-align: end !important;
    }
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Visitors List</li>
            </ol>
        </nav>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="">
                <div class="card animated fadeInUp">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h3 class="m-0">Archived Employees List</h3>
                    </div>

                    <!-- DataTable for visitor list -->
                    <div class="table-container">
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Position</th>
                                <th>Contact #</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Loop through visitors (PHP) -->
                            @foreach($archivedEmployees as $employee)
                            <tr class="animated fadeInUp">
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->company }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->contact_number }}</td>
                                <td>
                                    <form action="{{ route('employers_restore', $employee->id) }}" method="POST" class="d-inline-block restore-form">
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
                    "targets": [5],
                    "visible": true,
                    "responsivePriority": 1
                }
            ]
        });
    });
    $(document).ready(function () {
        $('.restore-btn').on('click', function (e) {
            e.preventDefault();

            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to Restore this employee?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form if confirmed
                }
            });
        });
    });
</script>

@endsection

