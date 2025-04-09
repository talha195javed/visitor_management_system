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
    Webcam.snap(function (data_uri) {
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

    fetch(uploadPhotoRoute.replace(':id', visitorId), {
        method: "POST",
        body: JSON.stringify({ photo: photoData }),
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        }
    })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.style.display = "none";

            if (data.success) {
                if (visibleFields['capture_id']) {
                    window.location.href = captureIdViewRoute.replace(':id', visitorId);
                } else if (visibleFields['emergency_contact']) {
                    window.location.href = emergencyContactRoute.replace(':id', visitorId);
                } else {
                    window.location.href = agreementRoute.replace(':id', visitorId);
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
