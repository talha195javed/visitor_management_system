@extends('layouts.front_app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg rounded-4 border-0 text-center" style="max-width: 500px; background: #fff;">

        <!-- Title -->
        <h2 class="fw-bold mb-3">Capture Visitor Photo</h2>
        <p class="text-muted">Align your face and click the button to capture.</p>

        <!-- Camera Display -->
        <div id="my_camera" class="border rounded-3 mx-auto" style="width: 320px; height: 240px;"></div>

        <input type="hidden" id="visitor_id" value="{{ $visitor->id }}">
        <input type="hidden" id="photo" name="photo">

        <!-- Capture Button -->
        <button type="button" class="btn btn-primary mt-3 capture-btn" onclick="takeSnapshot()">
            📸 Capture Photo
        </button>

        <!-- Image Preview -->
        <div class="mt-3">
            <label class="fw-semibold">Captured Image Preview:</label>
            <img id="captured_image" src="" alt="Captured Image" class="rounded-3 shadow-sm"
                 style="display:none; width:200px; opacity:0; transition: opacity 0.5s ease-in;">
        </div>

        <!-- Save Photo Button -->
        <button type="button" class="btn btn-success w-100 mt-3 save-btn" onclick="uploadPhoto()">
            💾 Save Photo
        </button>

        <!-- Loading Indicator -->
        <div id="loading" class="text-center mt-2" style="display:none;">
            <span class="spinner-border spinner-border-sm text-primary"></span> Uploading...
        </div>

    </div>
</div>

<!-- WebcamJS -->
<script type="text/javascript" src="https://cdn.rawgit.com/jhuckaby/webcamjs/master/webcam.min.js"></script>

<script type="text/javascript">
    // Webcam Setup
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    Webcam.attach('#my_camera');

    // Capture Snapshot
    function takeSnapshot() {
        Webcam.snap(function(data_uri) {
            let capturedImage = document.getElementById('captured_image');
            capturedImage.src = data_uri;
            capturedImage.style.display = 'block';
            capturedImage.style.opacity = '1';  // Fade-in effect
            document.getElementById('photo').value = data_uri; // Store base64 data
        });
    }

    // Upload Photo
    function uploadPhoto() {
        let visitorId = document.getElementById("visitor_id").value;
        let photoData = document.getElementById("photo").value;
        let loadingIndicator = document.getElementById("loading");

        if (!photoData) {
            alert("Please capture a photo first.");
            return;
        }

        loadingIndicator.style.display = "block"; // Show loading

        fetch("{{ route('visitor.storeCapturedImage', ':id') }}".replace(':id', visitorId), {
            method: "POST",
            body: JSON.stringify({ photo: photoData }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
            .then(response => response.json())
            .then(data => {
                loadingIndicator.style.display = "none"; // Hide loading
                if (data.success) {
                    alert("Photo uploaded successfully!");
                    window.location.href = "{{ route('visitor.captureIdView', ':id') }}".replace(':id', visitorId);
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                loadingIndicator.style.display = "none";
                console.error("Error:", error);
            });
    }
</script>

<!-- Custom Styling -->
<style>
    body {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        font-family: 'Poppins', sans-serif;
    }

    .capture-btn, .save-btn {
        font-size: 16px;
        font-weight: 600;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }

    .capture-btn:hover, .save-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.15);
    }

    .capture-btn:active, .save-btn:active {
        transform: translateY(1px);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
