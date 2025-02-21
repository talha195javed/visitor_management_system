@extends('layouts.front_app')

@section('content')
<div class="container-fluid d-flex flex-column flex-md-row vh-100">
    <!-- Left Column: Agreement Information -->
    <div class="col-md-6 d-flex justify-content-center align-items-center bg-custom-gradient text-white p-5 h-50 rounded-5">
        <div class="text-center">
            <h2 class="fw-bold mb-4">Visitor Agreement</h2>
            <p class="lead">Please read and accept the following terms and conditions to proceed with your visit.</p>
        </div>
    </div>

    <!-- Right Column: Agreement Form -->
    <div class="col-md-6 d-flex flex-column justify-content-center p-5">
        <form action="{{ route('visitor.storeAgreement', ['id' => $visitor->id]) }}" method="POST" class="shadow-lg p-4 rounded-4 bg-white">
            @csrf
            <div class="mb-4">
                <h4 class="text-dark">Privacy Policy & Terms and Conditions</h4>
                <p>
                    Welcome to our company! We value your privacy and ensure that your information is kept safe. By signing this agreement, you agree to our terms and conditions, which include details about your visit, security measures, data collection, and privacy protection.
                </p>
                <p>
                    Your data will only be used for the purpose of your visit and will not be shared with any third party without your consent. You will be provided with a visitor badge upon check-in, and security staff may assist you during your stay.
                </p>
                <p>
                    Please acknowledge that you have read and agreed to the above terms before proceeding.
                </p>
            </div>

            <div class="form-group d-flex align-items-center mb-4">
                <input type="checkbox" name="privacy_policy_agreement" id="privacy_policy_agreement" class="form-check-input">
                <label for="privacy_policy_agreement" class="ms-3">I agree to the <strong>Privacy Policy</strong> and <strong>Terms & Conditions</strong>.</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3">Confirm and Proceed</button>
        </form>
    </div>
</div>
<style>
    body {
        background: linear-gradient(to right, #c6dbed, #00f2fe);
        font-family: 'Poppins', sans-serif;
    }
    .bg-custom-gradient {
        background: linear-gradient(135deg, #f06, #48c6ef, #6f86d6) !important;
    }
    .text-white { color: white !important; }
    .form-check-input {
        transform: scale(1.2);
    }
    .btn-primary {
        background: linear-gradient(135deg, #f06, #48c6ef);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #e60578, #36b3ef);
        transform: scale(1.05);
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("form").addEventListener("submit", function (event) {
            let checkbox = document.getElementById("privacy_policy_agreement");
            if (!checkbox.checked) {
                event.preventDefault(); // Prevent form submission

                Swal.fire({
                    title: "Agreement Required",
                    text: "You must agree to the Privacy Policy & Terms and Conditions to proceed.",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#d33",
                    background: "#fff8e1",
                });
            }
        });
    });
</script>
@endsection
