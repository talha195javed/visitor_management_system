@extends('layouts.front_app')

@section('content')
<div id="mainScreen" class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">
<div class="container-fluid d-flex justify-content-center align-items-center vh-100" style="background: url('path-to-your-dark-image.jpg') no-repeat center center; background-size: cover;">
    <div class="col-md-8 col-lg-6 d-flex justify-content-center align-items-center p-5">
        @php
        use App\Models\FieldSetting;
        $visibleFields = FieldSetting::where('is_visible', true)->pluck('field_name')->toArray();
        @endphp

        <form id="progressiveForm" href="{{ route('visitor.storeCheckIn') }}" method="POST" enctype="multipart/form-data" class="bg-dark p-5 rounded-5 shadow-lg w-100">
            @csrf

            <div class="text-center mb-4">
                <h3 class="fw-bold text-white mb-4">Visitor Registration</h3>
                <p class="text-white-50">Please enter your email to check if you're pre-registered, or fill in your details below.</p>
            </div>

            <!-- Pre-Registration Check -->
            <div id="checkField" class="form-group mb-4 text-center">
                <input type="email" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg mb-2" name="check_email" id="check_email" placeholder="Enter your Email" required>

                <div class="d-flex justify-content-center mt-3">
                    <button type="button" id="checkEmailBtn" class="btn btn-light w-50 py-2 btn-lg shadow-lg">
                        <span id="checkEmailText">Check</span>
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </div>

            <input type="hidden" id="visitor_id" name="visitor_id" value="">

            <!-- Form Fields (Initially Hidden) -->
            <div id="formFields" style="display: none;">
                @if(in_array('full_name', $visibleFields))
                <div id="nameField" class="form-group align-items-center mb-3">
                    <label class="mb-1">Full Name</label>
                    <input type="text" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg" name="full_name" id="full_name" placeholder="Full Name" required>
                </div>
                @endif

                @if(in_array('company', $visibleFields))
                <div id="companyField" class="form-group align-items-center mb-3">
                    <label class="mb-1">Company you have to Visit</label>
                    <input type="text" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg" name="company" id="company" placeholder="Office you have to Visit" required>
                </div>
                @endif

                @if(in_array('email', $visibleFields))
                <div id="emailField" class="form-group align-items-center mb-3">
                    <label class="mb-1">Email</label>
                    <input type="email" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg" name="email" id="email" placeholder="Email" required>
                </div>
                @endif

                @if(in_array('phone', $visibleFields))
                <div id="phoneField" class="form-group align-items-center mb-3">
                    <label class="mb-1">Contact Number</label>
                    <input type="text" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg" name="phone" id="phone" placeholder="Phone" required>
                </div>
                @endif

                @if(in_array('id_type', $visibleFields))
                <div id="idField" class="form-group align-items-center mb-3">
                    <label class="mb-1">Identification Type</label>
                    <select name="id_type" id="id_type" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg" required>
                        <option value="">Select ID Type</option>
                        <option value="emirates_id">Emirates ID</option>
                        <option value="passport">Passport</option>
                        <option value="cnic">National CNIC</option>
                    </select>
                </div>
                @endif

                @if(in_array('identification_number', $visibleFields))
                <div id="idNumberField" class="form-group align-items-center mb-4">
                    <label class="mb-1">Identification Number</label>
                    <input type="text" class="form-control bg-transparent text-white border-light form-control-lg shadow-lg" name="identification_number" id="identification_number" placeholder="Enter ID Number" required>
                </div>
                @endif

                <!-- Submit Button -->
                <div id="submitButton" class="mt-3">
                    <button type="submit" class="btn btn-light w-100 py-2 btn-lg shadow-lg">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
@if ($errors->has('email'))
<script>
    Swal.fire({
        title: "Duplicate Email",
        text: "The email has already been taken.",
        icon: "error",
        confirmButtonText: "OK"
    });
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkEmailBtn = document.getElementById("checkEmailBtn");
        const checkEmailInput = document.getElementById("check_email");
        const checkFieldContainer = document.getElementById("checkField");
        const formFields = document.getElementById("formFields");
        const loadingSpinner = document.getElementById("loadingSpinner");
        const checkEmailText = document.getElementById("checkEmailText");

        checkEmailBtn.addEventListener("click", function () {
            let email = checkEmailInput.value.trim();
            if (email !== "") {
                checkEmailText.classList.add("d-none");
                loadingSpinner.classList.remove("d-none");

                fetch("{{ route('visitor.checkPreRegistered') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email: email })
                })
                    .then(response => response.json())
                    .then(data => {
                        checkEmailText.classList.remove("d-none");
                        loadingSpinner.classList.add("d-none");

                        if (data.success) {
                            let visitor = data.visitor;
                            document.getElementById("visitor_id").value = visitor.id;

                            // Update only visible fields with visitor data
                            let fields = ["full_name", "company", "email", "phone", "id_type", "identification_number"];
                            fields.forEach(field => {
                                let input = document.getElementById(field);
                                // Check if field is visible
                                if (input && input.closest('.form-group') && input.closest('.form-group').style.display !== 'none') {
                                    if (visitor[field] && visitor[field] !== "") {
                                        input.value = visitor[field];
                                    }
                                }
                            });

                            Swal.fire("Success", "Visitor details retrieved.", "success");
                        } else {
                            Swal.fire("Not Found", "Visitor not found. Please enter details manually.", "warning");
                        }

                        // Hide check field and show full form
                        checkFieldContainer.style.setProperty("display", "none", "important");

                        formFields.style.display = "block";
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        checkEmailText.classList.remove("d-none");
                        loadingSpinner.classList.add("d-none");
                        Swal.fire("Error", "An error occurred while checking. Please try again.", "error");
                    });
            }
        });
    });

</script>

<style>

    body {
        font-family: 'Poppins', sans-serif;
    }
    .container-fluid {
        background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black overlay */
    }
    .bg-dark {
        background-color: rgba(0, 0, 0, 0.7); /* Dark background for form */
    }
    .form-control {
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border: 1px solid #444;
        transition: all 0.3s ease;
        padding: 1rem; /* Increased padding */
    }
    .form-control:focus {
        border-color: #00f2fe;
        box-shadow: 0 0 10px rgba(0, 242, 254, 0.8);
    }
    .btn-light {
        background-color: #48c6ef;
        border-color: #48c6ef;
        transition: all 0.3s ease;
    }
    .btn-light:hover {
        background-color: #6f86d6;
        border-color: #6f86d6;
    }
    .form-control-lg {
        font-size: 1.1rem;
        padding: 1rem 1.25rem;
        border-radius: 12px;
    }
    .btn-lg {
        font-size: 1.2rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
    }
    .shadow-lg {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .col-md-8, .col-lg-6 {
        max-width: 600px; /* Wider form */
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
<script>
    document.querySelector(".navbar").classList.add("navbar-hidden");
</script>

@endsection
