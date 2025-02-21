@extends('layouts.app')

@section('content')
<style>
    .visitor-card {
        max-width: 800px;
        margin: auto;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease-in-out;
    }

    .visitor-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }

    .badge-status {
        font-size: 1rem;
        padding: 8px 15px;
        border-radius: 20px;
    }

    .btn-back {
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        transform: translateX(-5px);
    }
    .profile-img, .id-img {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        object-fit: cover;
        border: 3px solid #ddd;
    }
    .id-img {
        width: 180px;
        height: 120px;
        border-radius: 5px;
    }
</style>

<main id="main" class="main">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card visitor-card animated fadeInUp">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>{{ $visitor->full_name }}'s Details</h3>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <img src="{{ $photoPath }}" alt="Visitor Photo" class="profile-img">
                        <img src="{{ $idPhotoPath }}" alt="ID Photo" class="id-img">
                    </div>
                    <div class="card-body text-center">

                        <h4>{{ $visitor->full_name }}</h4>
                        <p><strong>Email:</strong> {{ $visitor->email }}</p>
                        <p><strong>Phone:</strong> {{ $visitor->phone ?? 'N/A' }}</p>
                        <p><strong>Check-in Time:</strong> {{ $visitor->check_in_time ?? 'N/A' }}</p>
                        <p><strong>Check-out Time:</strong> {{ $visitor->check_out_time ?? 'N/A' }}</p>
                        <p><strong>Company Visited:</strong> {{ $visitor->company ?? 'N/A' }}</p>
<h3> Emergency Details</h3>
                        <p>
                            <span class="badge badge-status {{ $visitor->check_out_time == '' ? 'bg-success' : 'bg-warning' }}">
                                 @if($visitor->check_out_time == '')
                                                {{ 'Checked In' }}
                                                @else
                                                {{ 'Checked Out' }}
                                                @endif
                            </span>
                        </p>

                        <a href="{{ route('visitors.admin_list') }}" class="btn btn-secondary btn-back">← Back to Visitors</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
