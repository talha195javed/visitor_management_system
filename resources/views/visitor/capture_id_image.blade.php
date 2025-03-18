@extends('layouts.front_app')

@section('content')
<div id="mainScreen" class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">    <div class="card p-4 shadow-lg rounded-4 border-0" style="max-width: 500px; background: #fff; transition: 0.3s;">
    <div class="card p-4 shadow-lg rounded-4 border-0 text-center" style="max-width: 500px; background: #fff;">

        <!-- Title -->
        <h2 class="fw-bold mb-3">Capture Visitor ID</h2>
        <p class="text-muted">Align the ID properly and capture the image.</p>

        <!-- Webcam Display -->
        <div id="my_camera" class="border rounded-3 mx-auto" style="width: 320px; height: 240px;"></div>

        <!-- Overlay Frame for ID alignment -->
        <!-- Overlay Frame for ID alignment (Now Invisible) -->
        <div id="alignment_frame" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 200px; z-index: 10;"></div>

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

<!-- WebcamJS -->
<script type="text/javascript" src="https://cdn.rawgit.com/jhuckaby/webcamjs/master/webcam.min.js"></script>

<!-- OpenCV.js -->
<script async src="https://docs.opencv.org/master/opencv.js"></script>
<script>
    var visibleFields = @json($visibleFields);
</script>
<script type="text/javascript">
    // Initialize the webcam
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('#my_camera');

    // Setup OpenCV.js
    cv.onRuntimeInitialized = () => {
        console.log("OpenCV.js is ready!");
        startVideoProcessing();
    };

    // Start video processing (real-time detection)
    function startVideoProcessing() {
        const video = document.getElementById('my_camera'); // Webcam video element
        const frameWidth = 320;
        const frameHeight = 240;
        const overlay = document.getElementById('alignment_frame');
        const message = document.getElementById('alignment_message');

        let canvas = document.createElement('canvas');
        canvas.width = frameWidth;
        canvas.height = frameHeight;
        let context = canvas.getContext('2d');
        document.body.appendChild(canvas); // Temporary canvas for OpenCV

        function processFrame() {
            context.drawImage(video, 0, 0, frameWidth, frameHeight);
            let src = cv.imread(canvas);
            let gray = new cv.Mat();
            let edges = new cv.Mat();
            let contours = new cv.MatVector();

            // Convert to grayscale
            cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
            cv.Canny(gray, edges, 50, 100, 3, false);

            // Find contours (edges)
            cv.findContours(edges, contours, new cv.Mat(), cv.RETR_LIST, cv.CHAIN_APPROX_SIMPLE);

            // Check if any contours match the shape of an ID card (rectangle)
            let isCardAligned = false;
            for (let i = 0; i < contours.size(); i++) {
                let cnt = contours.get(i);
                let approx = new cv.Mat();
                let epsilon = 0.02 * cv.arcLength(cnt, true);
                cv.approxPolyDP(cnt, approx, epsilon, true);

                // Check if we found a rectangle-like contour
                if (approx.rows == 4) {
                    let rect = cv.boundingRect(approx);
                    let width = rect.width;
                    let height = rect.height;

                    // Check if the rectangle fits within the target area (300x200)
                    if (width >= 150 && width <= 300 && height >= 100 && height <= 200) {
                        isCardAligned = true;
                        // Optional: Draw the bounding rectangle
                        cv.drawContours(src, contours, i, [255, 0, 0, 255], 2, cv.LINE_8);
                    }
                }
                approx.delete();
            }

            // Show instructions if the card is not aligned
            if (isCardAligned) {
                message.style.display = 'none';
                document.getElementById('capture-btn').disabled = false;
            } else {
                message.style.display = 'block';
                document.getElementById('capture-btn').disabled = true;
            }

            cv.imshow(canvas, src);
            src.delete();
            gray.delete();
            edges.delete();
            contours.delete();
        }

        setInterval(processFrame, 100);
    }

    // Capture the snapshot
    function takeSnapshot() {
        Webcam.snap(function(data_uri) {
            let capturedImage = document.getElementById('captured_image');
            capturedImage.src = data_uri;
            capturedImage.style.display = 'block';
            capturedImage.style.opacity = '1';  // Fade-in effect
            document.getElementById('photo').value = data_uri;
        });
    }

    // Upload the captured photo
    function uploadPhoto() {
        let visitorId = document.getElementById("visitor_id").value;
        let photoData = document.getElementById("photo").value;
        let loadingIndicator = document.getElementById("loading");

        if (!photoData) {
            alert("Please capture an ID image first.");
            return;
        }

        loadingIndicator.style.display = "block";

        fetch("{{ route('visitor.storeCapturedIdImage', ':id') }}".replace(':id', visitorId), {
            method: "POST",
            body: JSON.stringify({ photo: photoData }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
            .then(response => response.json())
            .then(data => {
                loadingIndicator.style.display = "none";
                if (data.success) {
                    if (visibleFields['emergency_contact']) {
                        window.location.href = "{{ route('visitor.showEmergencyContact', ':id') }}".replace(':id', visitorId);
                    } else {
                        window.location.href = "{{ route('visitor.agreement', ':id') }}".replace(':id', visitorId);
                    }
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

    #mainScreen {
        background: url('{{ asset('assets/img/checkin6.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
        color: #fff;
    }
    .navbar-hidden {
        display: none !important; /* Hide the navbar */
    }
</style>

@endsection
