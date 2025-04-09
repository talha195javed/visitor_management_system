@extends('layouts.front_app')

@section('content')
<div id="mainScreen"
     class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">
    <div class="card p-4 shadow-lg rounded-4 border-0" style="max-width: 500px; background: #fff; transition: 0.3s;">
        <div class="card p-4 shadow-lg rounded-4 border-0 text-center" style="max-width: 500px; background: #fff;">

            <!-- Title -->
            <h2 class="fw-bold mb-3">Capture Visitor ID</h2>
            <p class="text-muted">Align the ID properly and capture the image.</p>

            <!-- Webcam Display -->
            <div id="my_camera" class="border rounded-3 mx-auto" style="width: 320px; height: 240px;"></div>

            <!-- Overlay Frame for ID alignment -->
            <!-- Overlay Frame for ID alignment (Now Invisible) -->
            <div id="alignment_frame"
                 style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 200px; z-index: 10;"></div>

            <input type="hidden" id="visitor_id" value="{{ $visitor->id }}">
            <input type="hidden" id="photo" name="photo">

            <!-- Capture Button -->
            <button type="button" class="btn btn-primary mt-3 capture-btn" onclick="takeSnapshot()">
                📸 Capture ID Image
            </button>

            <!-- Image Preview -->
            <div class="mt-3">
                <label class="fw-semibold">Captured Image Preview:</label>
                <img id="captured_image" src="" alt="Captured Image" class="rounded-3 shadow-sm"
                     style="display:none; width:200px; opacity:0; transition: opacity 0.5s ease-in;">
            </div>

            <!-- Instructions for Alignment -->
            <p id="alignment_message" style="font-size: 16px; font-weight: bold; color: #ff0000; display: none;">
                Please align the ID card within the frame and keep it steady.
            </p>

            <!-- Save Photo Button -->
            <button type="button" class="btn btn-success w-100 mt-3 save-btn" onclick="uploadPhoto()">
                💾 Save ID Image
            </button>

            <!-- Loading Indicator -->
            <div id="loading" class="text-center mt-2" style="display:none;">
                <span class="spinner-border spinner-border-sm text-primary"></span> Uploading...
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.rawgit.com/jhuckaby/webcamjs/master/webcam.min.js"></script>
    <script async src="https://docs.opencv.org/master/opencv.js"></script>

    <script>
        const visibleFields = @json($visibleFields);
        const csrfToken = "{{ csrf_token() }}";
        const uploadIdRoute = "{{ route('visitor.storeCapturedIdImage', ':id') }}";
        const emergencyContactRoute = "{{ route('visitor.showEmergencyContact', ':id') }}";
        const agreementRoute = "{{ route('visitor.agreement', ':id') }}";
    </script>

    <script src="{{ asset('js/capture_id.js') }}"></script>
    @endpush
    @push('styles')
    <link rel="stylesheet" href="{{ asset('/css/capture_id.css') }}">
    @endpush
    @endsection
