@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table_list.css') }}">
@endpush

@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <br>
        <h1>Profile</h1>
        <br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Visitors List</li>
            </ol>
        </nav>
    </div>
    <br>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="">
                <!-- Accordion for visitor filter -->
                <!--<div class="accordion" id="visitorFilterAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
                                Filter Visitors
                            </button>
                        </h2>
                        <div id="filterCollapse" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#visitorFilterAccordion">
                            <div class="accordion-body">
                                <form method="GET" action="#">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">All</option>
                                            <option value="checked_in">Checked In</option>
                                            <option value="checked_out">Checked Out</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>-->

                <div class="card animated fadeInUp">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h3 class="m-0">Visitor List</h3>
                        <a href="{{ route('visitors.admin_pre_register') }}" class="btn btn-light btn-hover">Pre Register Visitor</a>
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
                            @foreach($visitors as $visitor)
                            <tr class="animated fadeInUp">
                                <td>{{ $visitor->full_name }}</td>
                                <td>{{ $visitor->email }}</td>
                                <td>{{ $visitor->identification_number }}</td>
                                <td>{{ $visitor->phone }}</td>
                                <td>
                                            <span class="badge {{ $visitor->check_out_time == '' && $visitor->check_in_time == '' ? 'bg-primary' :
        ($visitor->check_out_time == '' ? 'bg-success' : 'bg-warning') }}">
                                                @if($visitor->check_out_time == '' && $visitor->check_in_time == '')
                                                    {{ 'Waiting' }}
                                                @elseif ($visitor->check_out_time == '')
                                                    {{ 'Checked In' }}
                                                @else
                                                    {{ 'Checked Out' }}
                                                @endif
                                            </span>
                                </td>
                                <td style="width: 10% !important;">
                                    <!-- View Button -->
                                    <a href="{{ route('visitors.show', $visitor->id) }}" class="btn btn-primary btn-sm btn-hover">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Archive Button -->
                                    <form action="{{ route('visitors.archive', $visitor->id) }}" method="POST" class="d-inline-block archive-form">
                                    @csrf
                                        <button type="button" class="btn btn-danger btn-sm btn-hover archive-btn">
                                            <i class="fas fa-trash-alt"></i>
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
                    "targets": [5],
                    "visible": true,
                    "responsivePriority": 1
                }
            ]
        });
    });

    $(document).ready(function () {
        $('.archive-btn').on('click', function (e) {
            e.preventDefault(); // Prevents immediate form submission

            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to archive this Visitor?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form[0].submit(); // Ensure form is submitted correctly
                }
            });
        });
    });

</script>

@endsection

