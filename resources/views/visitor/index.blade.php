@extends('layouts.front_app')

@section('content')

<!-- Full-screen split layout -->
<div class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">
    <!-- Left side with gradient background -->
    <div class="col-12 col-md-6 gradient-bg d-flex flex-column justify-content-center align-items-center text-white p-5 text-center text-md-start left">
        <h1 class="display-4 fw-bold animate__animated animate__fadeInDown">
            Welcome to <br> Visitor Management System
        </h1>
        <p class="mt-3 animate__animated animate__fadeIn">A seamless and secure check-in experience.</p>
        <button id="enterButton" class="enter-button animate__animated animate__pulse animate__infinite mt-4">
            Enter
        </button>
    </div>

    <!-- Right side with check-in/out options -->
    <div id="mainScreen" class="col-12 col-md-6 d-flex justify-content-center align-items-center vh-100 right">
        <div class="check-in-out-card text-center">
            <h2 class="mb-3">Visitor Management</h2>
            <p class="text-muted mb-4">Welcome! Please check in or check out to continue.</p>

            <div class="d-grid gap-3">
                <a href="{{ route('visitor.checkin') }}" class="btn check-in-btn btn-lg fw-semibold">
                    <i class="fas fa-sign-in-alt me-2"></i> Check In
                </a>
                <a href="{{ route('visitor.checkout') }}" class="btn check-out-btn btn-lg fw-semibold">
                    <i class="fas fa-sign-out-alt me-2"></i> Check Out
                </a>
            </div>
<br><br>
            <div class="check-in-out-card text-center">

                <p class="text-muted mb-4">Experience seamless and secure entry with Touchless Check-In. Simply scan the QR code to log in instantly—no contact, no hassle.</p>

                <div class="d-grid gap-3">
                    <img src="{{ asset('assets/img/scanner.png') }}" alt="QR Code" class="img-fluid">
                </div>


            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 767px) {
        .container-fluid {
            padding-top: 20%;
        }
        .left {
            display: none;
        }
        .right {
            display: block;
        }
    }
</style>

<script>
    // Add event listener to handle "Enter" key press
    document.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            let left = document.querySelector('.left');
            let right = document.querySelector('.right');

            // Toggle classes
            if (left && right) {
                left.classList.add('d-none');
                right.classList.remove('d-none');
            }
        }
    });
</script>
<script>
    document.getElementById("enterButton").addEventListener("click", function () {
        let mainScreen = document.getElementById("mainScreen");
        mainScreen.classList.remove("d-none");
        mainScreen.classList.add("d-flex", "animate__animated", "animate__fadeInUp");
    });
</script>

@endsection
