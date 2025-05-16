@extends('layouts.front_app')

@section('content')

<!-- Welcome Screen -->
<div id="welcomeScreen" class="welcome-screen d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="position-absolute top-0 end-0 m-3">
        <button id="logoutButton" class="btn btn-danger btn-sm">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
    <h1 class="display-1 text-white animate__animated animate__fadeInDown">Welcome</h1>
    <p class="text-white animate__animated animate__fadeIn font-bold">Experience Seamless Visitor Management</p>
    <button id="enterButton" class="enter-button animate__animated animate__pulse animate__infinite mt-4">
        Enter
    </button>
</div>

@php
use App\Models\ScreenSetting;
$visibleFields = ScreenSetting::where('is_visible', true)->pluck('screen_name')->toArray();
@endphp

<!-- Main Content -->
<div id="mainScreen"
     class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0 d-none">

    <!-- Left Side - Buttons Section -->
    <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center text-white p-5 left-side">
        <h2 class="mb-3">Visitor Management</h2>
        <p class="text-light mb-4">Welcome! Please check in or check out to continue.</p>

        <div class="d-grid gap-3">
            <a href="{{ route('visitor.checkin') }}" class="btn check-in-btn btn-lg fw-semibold">
                <i class="fas fa-sign-in-alt me-2"></i> Check In
            </a>
            @if(in_array('check_out', $visibleFields))
            <a href="{{ route('visitor.checkout') }}" class="btn check-out-btn btn-lg fw-semibold">
                <i class="fas fa-sign-out-alt me-2"></i> Check Out
            </a>
            @endif
        </div>
    </div>

    <!-- Right Side - QR Scanner Section -->
    <div class="col-12 col-md-6 d-flex justify-content-center align-items-center position-relative right-side">
        <div class="scanner-container text-center animate__animated animate__fadeInUp position-relative">
            <p class="text-light mb-4 ri-font-size-2">Experience seamless and secure entry with Touchless Check-In.
                Simply scan the QR code to log in instantly—no contact, no hassle.</p>
            <br><br>
            <img src="{{ asset('assets/img/scanner.png') }}" alt="QR Code" class="img-fluid scanner-img">
        </div>
    </div>
</div>

@push('styles')
<style>
    #logoutButton {
        z-index: 1050;
    }

    #mainScreen {
        background: url('{{ asset('assets/visitor_photos/main_screen_image.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    .welcome-screen {
        position: fixed;
        width: 100%;
        height: 100vh;
        background: url('{{ asset('assets/visitor_photos/welcome_screen_image.jpg') }}') no-repeat center center;
        background-size: cover;
        background-attachment: fixed;
        text-align: center;
        z-index: 1000;
        transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
    }
</style>
<link rel="stylesheet" href="{{ asset('/css/index_visitor.css') }}">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/index_visitor.js') }}"></script>
<script>
    $(document).ready(function() {
        // Ensure logout button is visible if logged in
        if (localStorage.getItem('client_logged_in') === 'true') {
            $('#logoutButton').show();
        } else {
            $('#logoutButton').hide();
        }

        // Safe event delegation for logout
        $(document).on('click', '#logoutButton', function() {
            console.log('Logout button clicked');

            // Clear client data from localStorage
            localStorage.removeItem('client_logged_in');
            localStorage.removeItem('client_id');

            // Redirect to client logout route
            window.location.href = "{{ route('client.logout') }}";
        });
    });
</script>
@endpush

@endsection
