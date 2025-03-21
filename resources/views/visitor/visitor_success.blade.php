@extends('layouts.front_app')

@section('content')
<div class="mainScreen container-fluid d-flex flex-column flex-md-row vh-100">
    <div class="card shadow-lg p-5 rounded-4" style="max-width: 800px;">
        <div class="text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Success" width="100" class="mb-4">
            <h2 class="fw-bold text-success">Check-In Complete!</h2>
            <p class="lead mt-3">Thank you <span class="fw-bold text-primary">{{ $visitor->full_name }}</span> for completing the check-in process. We are excited to have you with us today!</p>

            <div class="mt-4">
                <h4 class="text-primary">Notification Sent</h4>
                <p>An email has been sent to the relevant department, notifying them of your arrival. Please wait in the lobby, and someone will assist you shortly.</p>
            </div>

            <div class="mt-5">
                <a href="{{ route('visitor.home') }}" class="btn btn-lg btn-primary px-5 py-3">Back to Home</a>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #74EBD5 0%, #9FACE6 100%);
        font-family: 'Poppins', sans-serif;
        color: #333;
    }

    .card {
        background: #ffffff;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #36D1DC 0%, #5B86E5 100%);
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5B86E5 0%, #36D1DC 100%);
        transform: translateY(-2px);
        transition: 0.3s;
    }
    .mainScreen {
        background: url('{{ asset('assets/img/checkin6.jpg') }}') no-repeat center center;
        background-size: cover;
    }
    .navbar-hidden {
        display: none !important; /* Hide the navbar */
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: 'Welcome!',
        text: 'Your check-in is successful.',
        icon: 'success',
        background: '#ffffff',
        confirmButtonColor: '#3085d6',
        timer: 2000,
        timerProgressBar: true
    });

    setTimeout(function() {
        window.location.href = "{{ route('visitor.home') }}";
    }, 20000);
</script>
@endsection
