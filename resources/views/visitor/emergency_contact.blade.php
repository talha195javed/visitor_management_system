@extends('layouts.front_app')

@section('content')
<div class="container-fluid d-flex flex-column flex-md-row vh-100">
    <!-- Left Side: Instructions -->
    <div class="col-md-6 d-flex justify-content-center align-items-center bg-custom-gradient text-white p-5 h-50 rounded-5">
        <div class="text-center">
            <h3 id="instructionText" class="fw-bold mb-4">Please enter the emergency contact details and press the tick ✔</h3>
        </div>
    </div>

    <!-- Right Side: Emergency Contact Form -->
    <div class="col-md-6 d-flex flex-column justify-content-center p-5">
        <form action="{{ route('visitor.storeEmergencyContact', $visitor->id) }}" method="POST" class="shadow-lg p-4 rounded-4 bg-white">
            @csrf

            <!-- Emergency Contact Name -->
            <div class="form-group d-flex align-items-center mb-3">
                <input type="text" class="form-control" name="emergency_name" id="emergency_name" placeholder="Emergency Contact Name" value="{{ old('emergency_name') }}" required>
            </div>

            <!-- Emergency Contact Phone -->
            <div id="emergencyPhoneField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="text" class="form-control" name="emergency_phone" id="emergency_phone" placeholder="Emergency Contact Phone" value="{{ old('emergency_phone') }}" required>
            </div>

            <!-- Emergency Relation -->
            <div id="emergencyRelationField" class="form-group d-flex align-items-center mb-3 hidden">
                <input type="text" class="form-control" name="emergency_relation" id="emergency_relation" placeholder="Relation to Visitor" value="{{ old('emergency_relation') }}" required>
            </div>

            <!-- Submit Button -->
            <div id="submitButton" >
                <button type="submit" class="btn btn-primary w-100 py-3">Save Emergency Contact</button>
            </div>
        </form>
    </div>
</div>

@if ($errors->has('emergency_name') || $errors->has('emergency_phone') || $errors->has('emergency_relation'))
<script>
    Swal.fire({
        title: "Missing Information",
        text: "Please fill in all the required fields.",
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
        const form = document.querySelector("form");

        const instructionMessages = {
            emergency_name: "Enter the name of the emergency contact and press the tick ✔",
            emergency_phone: "Enter the emergency contact's phone number and press the tick ✔",
            emergency_relation: "Enter the relation of the emergency contact to the visitor and press the tick ✔"
        };

        // Enable next field when checkbox is checked
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
