@extends('layouts.front_app')

@section('content')
<div class="container-fluid d-flex flex-column flex-md-row vh-100">
    <!-- Left Side: Dynamic Instructions -->
    <div class="col-md-6 d-flex justify-content-center align-items-center bg-custom-gradient text-white p-5 h-50 rounded-5">
        <div class="text-center">
            <h3 id="instructionText" class="fw-bold mb-4">Please first enter your email and check if you're pre-registered</h3>
        </div>
    </div>

    <!-- Right Side: Progressive Form -->
    <div class="col-md-6 d-flex flex-column justify-content-center p-5">
        <form id="progressiveForm" href="{{ route('visitor.storeCheckIn') }}" method="POST" enctype="multipart/form-data" class="shadow-lg p-4 rounded-4 bg-white">
            @csrf

            <!-- Pre-Registration Check -->
            <div class="form-group d-flex align-items-center mb-3">
                <input type="email" class="form-control" name="check_email" id="check_email" placeholder="Enter your Email" required>
                <button type="button" id="checkEmailBtn" class="btn btn-outline-primary ms-3">
                    <span id="checkEmailText">Check</span>
                    <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
            <input type="hidden" id="visitor_id" name="visitor_id" value="">
            <!-- Step 1: Full Name -->
            <div id="nameField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" disabled>
                <input type="checkbox" class="ms-3 form-check-input step-checkbox" data-next="companyField">
            </div>

            <!-- Step 2: Company -->
            <div id="companyField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="text" class="form-control" name="company" id="company" placeholder="Office you have to Visit" disabled>
                <input type="checkbox" class="ms-3 form-check-input step-checkbox" data-next="emailField">
            </div>

            <!-- Step 3: Email -->
            <div id="emailField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" disabled>
                <input type="checkbox" class="ms-3 form-check-input step-checkbox" data-next="phoneField">
            </div>

            <!-- Step 4: Phone -->
            <div id="phoneField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" disabled>
                <input type="checkbox" class="ms-3 form-check-input step-checkbox" data-next="idField">
            </div>

            <!-- Step 5: ID Type -->
            <div id="idField" class="form-group d-flex align-items-center mb-3 hidden">
                <select name="id_type" id="id_type" class="form-control" disabled>
                    <option value="">Select ID Type</option>
                    <option value="emirates_id">Emirates ID</option>
                    <option value="passport">Passport</option>
                    <option value="cnic">National CNIC</option>
                </select>
                <input type="checkbox" class="ms-3 form-check-input step-checkbox" data-next="idNumberField">
            </div>

            <!-- Step 6: ID Number -->
            <div id="idNumberField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="text" class="form-control" name="identification_number" id="identification_number" placeholder="Enter ID Number" disabled>
                <input type="checkbox" class="ms-3 form-check-input step-checkbox" data-next="submitButton">
            </div>

            <!-- Submit Button -->
            <div id="submitButton" class="hidden">
                <button type="submit" class="btn btn-primary w-100 py-3">Submit</button>
            </div>
        </form>
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
        const instructionText = document.getElementById("instructionText");
        const checkboxes = document.querySelectorAll(".step-checkbox");
        const checkEmailBtn = document.getElementById("checkEmailBtn");
        const checkEmailInput = document.getElementById("check_email");
        const checkEmailText = document.getElementById("checkEmailText");
        const loadingSpinner = document.getElementById("loadingSpinner");
        const form = document.getElementById("progressiveForm");

        // Instruction Messages
        const instructionMessages = {
            full_name: "Enter your name and press the tick ✔️",
            company: "Enter Office Details you have to visit and press the tick ✔️",
            email: "Enter your email and press the tick ✔️",
            phone: "Enter your phone number and press the tick ✔️",
            id_type: "Select your ID type and press the tick ✔️",
            identification_number: "Enter your ID number and press the tick ✔️"
        };

        // Pre-Registration Check
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
                            console.log(visitor.id);
                            let fields = ["full_name", "company", "email", "phone", "id_type", "identification_number"];
                            document.getElementById("visitor_id").value = visitor.id;

                            fields.forEach(field => {
                                let input = document.getElementById(field);
                                let checkbox = input.nextElementSibling; // The checkbox next to input
                                let fieldContainer = input.closest(".form-group");

                                if (visitor[field] && visitor[field] !== "") {
                                    input.value = visitor[field];
                                    input.disabled = false;
                                    checkbox.checked = true;
                                    fieldContainer.classList.remove("hidden");

                                    enableNextField(checkbox);
                                }
                            });

                            instructionText.textContent = "Visitor details retrieved. Please verify and continue.";
                        } else {
                            document.getElementById("nameField").classList.remove("hidden");
                            document.getElementById("full_name").disabled = false;
                            instructionText.textContent = "Visitor not found. Please enter details manually.";
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        instructionText.textContent = "An error occurred while checking. Please try again.";
                    });
            }
        });

        // Submit Form with SweetAlert for empty fields
        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission to check fields

            let emptyFields = [];
            const requiredFields = ['full_name', 'company', 'email', 'phone', 'id_type', 'identification_number'];

            // Check if any required field is empty
            requiredFields.forEach(fieldName => {
                let field = document.getElementById(fieldName);
                if (field && !field.value) {
                    emptyFields.push(fieldName);
                }
            });

            // If any field is empty, show a SweetAlert
            if (emptyFields.length > 0) {
                Swal.fire({
                    title: "Missing Information",
                    text: "Please fill in all the required fields.",
                    icon: "error",
                    confirmButtonText: "Ok"
                });
            } else {
                // If all fields are filled, proceed with form submission
                form.submit();
            }
        });

        // Enable next field when checkbox is checked (either auto or manually)
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                let input = this.previousElementSibling; // The input field before the checkbox

                if (this.checked) {
                    if (!input.value.trim()) {
                        this.checked = false; // Prevent checking the box
                        Swal.fire({
                            icon: "warning",
                            title: "Incomplete Field",
                            text: "Please fill out this field before proceeding.",
                            confirmButtonText: "OK"
                        });
                        input.focus();
                        return;
                    }
                    enableNextField(this);
                }
            });
        });

        // Show instruction messages when fields are focused
        document.querySelectorAll("input, select").forEach(input => {
            input.addEventListener("focus", function () {
                if (instructionMessages[this.id]) {
                    instructionText.textContent = instructionMessages[this.id];
                }
            });
        });

        function enableNextField(checkbox) {
            let nextFieldId = checkbox.getAttribute("data-next");
            let nextField = document.getElementById(nextFieldId);

            if (nextField) {
                nextField.classList.remove("hidden");

                let input = nextField.querySelector("input, select");
                if (input) {
                    input.disabled = false;
                    input.focus();
                    instructionText.textContent = instructionMessages[input.id] || "Please proceed.";
                }
            }
        }
    });
</script>


<style>
    body {
        background: linear-gradient(to right, #c6dbed, #00f2fe);
        font-family: 'Poppins', sans-serif;
    }
    .bg-custom-gradient { background: linear-gradient(135deg, #f06, #48c6ef, #6f86d6); }
    .hidden { display: none; }
    .fade-in { animation: fadeIn 0.8s ease-in-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

@endsection
