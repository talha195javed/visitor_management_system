@extends('layouts.front_app')

@section('content')

<!-- Welcome Screen -->
<div id="welcomeScreen" class="welcome-screen d-flex flex-column justify-content-center align-items-center vh-100">
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
<div id="mainScreen" class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0 d-none">

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
            <p class="text-light mb-4 ri-font-size-2 ">Experience seamless and secure entry with Touchless Check-In. Simply scan the QR code to log in instantly—no contact, no hassle.</p>
<br><br>
            <img src="{{ asset('assets/img/scanner.png') }}" alt="QR Code" class="img-fluid scanner-img">
        </div>
    </div>
</div>


<style>
    /* Fullscreen container with dark theme background and gradient overlay */
    #mainScreen {
        background: url('{{ asset('assets/img/dark-theme-background.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    /* Gradient Overlay for darker background */
    .background-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4)); /* Gradient for a smoother dark effect */
        z-index: -1;
    }

    /* Left Side - Buttons Section */
    .left-side {
        text-align: center;
        animation: fadeInUp 1s ease-in-out;
    }

    .left-side h2 {
        font-size: 2rem;
        font-weight: bold;
    }

    .left-side .btn {
        color: #fff;
        border: 1px solid #fff;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .left-side .btn:hover {
        background-color: #ffcc00; /* Hover background color */
        color: #000;
        transform: scale(1.05); /* Scale up effect */
        box-shadow: 0 4px 20px rgba(255, 204, 0, 0.5); /* Glowing effect */
    }

    /* Right Side - Scanner Section */
    .right-side {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .scanner-container {
        position: relative;
        display: inline-block;
    }

    .scanner-img {
        width: 80%; /* Make sure it fits well */
        max-width: 400px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }

    .scanner-img:hover {
        transform: scale(1.1); /* Slight zoom effect */
        box-shadow: 0 0 30px rgba(255, 204, 0, 0.7); /* Glowing effect around the scanner */
    }

    .text-muted {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .left-side h2 {
            font-size: 1.5rem;
        }

        .scanner-img {
            width: 90%; /* Adjust width for smaller screens */
            max-width: 300px;
        }

        .check-in-btn, .check-out-btn {
            font-size: 1.1rem; /* Adjust font size for smaller screens */
        }
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

    /* Optional Dark Overlay */
    .welcome-screen::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Dark overlay for better contrast */
        z-index: -1;
    }

    .enter-button {
        padding: 12px 30px;
        font-size: 1.2rem;
        background-color: #ffcc00;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    }

    .hidden {
        opacity: 0;
        transform: translateY(-100%);
        pointer-events: none;
    }

    .visible {
        opacity: 1;
        transform: translateY(0);
    }
    .navbar-hidden {
        display: none;
    }
    .enter-button:hover {
        background-color: #28a745; /* Set the background color to the green shade */
        color: #fff; /* Change the text color to white */
        transform: scale(1.05); /* Scale up effect */
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.5); /* Glowing effect around the button */
    }
</style>

<script>
    let inactivityTimer;

    function showWelcomeScreen() {
        document.getElementById("welcomeScreen").classList.remove("hidden");
        document.getElementById("mainScreen").classList.add("d-none");
        document.querySelector(".navbar").classList.add("navbar-hidden");  // Hide navbar
    }

    function hideWelcomeScreen() {
        document.getElementById("welcomeScreen").classList.add("hidden");
        document.getElementById("mainScreen").classList.remove("d-none", "animate__fadeOutDown");
        document.getElementById("mainScreen").classList.add("d-flex", "animate__fadeInUp");
      //  document.querySelector(".navbar").classList.remove("navbar-hidden");  // Show navbar
        resetInactivityTimer();
    }

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(showWelcomeScreen, 20000);
    }

    document.getElementById("enterButton").addEventListener("click", hideWelcomeScreen);
    document.addEventListener("mousemove", resetInactivityTimer);
    document.addEventListener("keydown", resetInactivityTimer);

</script>

@endsection
