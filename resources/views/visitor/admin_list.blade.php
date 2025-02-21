<!-- visitors/list.blade.php -->
@extends('layouts.app')

@section('content')
<style>
    /* Responsive and modern table styling */
    .table-responsive {
        overflow-x: auto;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    .card {
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-hover {
        transition: all 0.3s ease;
    }

    .btn-hover:hover {
        transform: scale(1.1);
    }

    .fadeInUp {
        animation: fadeInUp 0.8s ease-in-out;
    }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .card-header h3 {
            font-size: 1.25rem;
        }
        .btn {
            font-size: 0.875rem;
        }
    }
</style>
<main id="main" class="main">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 w-100">
                <div class="card animated fadeInUp">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h3 class="m-0">Visitor List</h3>
                        <a href="#" class="btn btn-light btn-hover">Pre Register Visitor</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="bg-light">
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
                            @foreach($visitors as $visitor)
                            <tr class="animated fadeInUp">
                                <td>{{ $visitor->full_name }}</td>
                                <td>{{ $visitor->email }}</td>
                                <td>{{ $visitor->identification_number }}</td>
                                <td>{{ $visitor->phone }}</td>
                                <td>
                                            <span class="badge {{ $visitor->check_out_time == '' ? 'bg-success' : 'bg-warning' }}">
                                               @if($visitor->check_out_time == '')
                                                {{ 'Checked In' }}
                                                @else
                                                {{ 'Checked Out' }}
                                                @endif
                                            </span>
                                </td>
                                <td>
                                    <a href="{{ route('visitors.show', $visitor->id) }}" class="btn btn-primary btn-sm btn-hover">View</a>

                                    <form action="#" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm btn-hover">Archive</button>
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
@endsection
