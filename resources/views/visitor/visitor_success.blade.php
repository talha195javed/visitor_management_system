@extends('layouts.front_app')

@section('content')
<div class="mainScreen container-fluid d-flex flex-column flex-md-row vh-100">
    <div class="card shadow-lg p-5 rounded-4 text-center" style="max-width: 800px;">
        <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Success" width="100" class="mb-4">
        <h2 class="fw-bold text-success">Check-In Complete!</h2>
        <p class="lead mt-3">Thank you <span class="fw-bold text-primary">{{ $visitor->full_name }}</span> for checking in. We are excited to have you with us today!</p>

        <div class="receipt mt-4 p-4 border rounded shadow-sm bg-light">
            <h4 class="text-dark fw-bold">Visitor ID</h4>
            <p class="display-4 text-primary fw-bold">{{ $visitor->id }}</p>
            <p class="text-muted">Please remember your Visitor ID. You will need it for checkout.</p>
        </div>

        <div class="mt-4">
            <h4 class="text-primary">Notification Sent</h4>
            <p>An email has been sent to the relevant department notifying them of your arrival. Please wait in the lobby for assistance.</p>
        </div>

        <div class="mt-5">
            <a href="{{ route('visitor.home') }}" class="btn btn-lg btn-primary px-5 py-3">Back to Home</a>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/visitor_success.css') }}">
<style>
    .mainScreen {
        background: url('{{ asset('assets/visitor_photos/remaining_screen_image.jpg') }}') no-repeat center center;
        background-size: cover;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: 'Welcome!',
        text: 'Your check-in is successful. Remember your Visitor ID for checkout.',
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

@endpush
@endsection
