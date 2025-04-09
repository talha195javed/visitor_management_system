// public/js/capture_id.js

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

function startVideoProcessing() {
    const video = document.getElementById('my_camera');
    const frameWidth = 320;
    const frameHeight = 240;
    const overlay = document.getElementById('alignment_frame');
    const message = document.getElementById('alignment_message');

    let canvas = document.createElement('canvas');
    canvas.width = frameWidth;
    canvas.height = frameHeight;
    let context = canvas.getContext('2d');
    document.body.appendChild(canvas);

    function processFrame() {
        context.drawImage(video, 0, 0, frameWidth, frameHeight);
        let src = cv.imread(canvas);
        let gray = new cv.Mat();
        let edges = new cv.Mat();
        let contours = new cv.MatVector();

        cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
        cv.Canny(gray, edges, 50, 100, 3, false);
        cv.findContours(edges, contours, new cv.Mat(), cv.RETR_LIST, cv.CHAIN_APPROX_SIMPLE);

        let isCardAligned = false;
        for (let i = 0; i < contours.size(); i++) {
            let cnt = contours.get(i);
            let approx = new cv.Mat();
            let epsilon = 0.02 * cv.arcLength(cnt, true);
            cv.approxPolyDP(cnt, approx, epsilon, true);

            if (approx.rows == 4) {
                let rect = cv.boundingRect(approx);
                let width = rect.width;
                let height = rect.height;

                if (width >= 150 && width <= 300 && height >= 100 && height <= 200) {
                    isCardAligned = true;
                    cv.drawContours(src, contours, i, [255, 0, 0, 255], 2, cv.LINE_8);
                }
            }
            approx.delete();
        }

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

function takeSnapshot() {
    Webcam.snap(function (data_uri) {
        let capturedImage = document.getElementById('captured_image');
        capturedImage.src = data_uri;
        capturedImage.style.display = 'block';
        capturedImage.style.opacity = '1';
        document.getElementById('photo').value = data_uri;
    });
}

function uploadPhoto() {
    let visitorId = document.getElementById("visitor_id").value;
    let photoData = document.getElementById("photo").value;
    let loadingIndicator = document.getElementById("loading");

    if (!photoData) {
        alert("Please capture an ID image first.");
        return;
    }

    loadingIndicator.style.display = "block";

    fetch(uploadIdRoute.replace(':id', visitorId), {
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
                if (visibleFields['emergency_contact']) {
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
