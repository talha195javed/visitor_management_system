@extends('layouts.front_app')

@section('content')
<div class="mainScreen container-fluid d-flex flex-column flex-md-row vh-100">

    <div class="col-md-3">
    </div>

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

    <div class="col-md-3">
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('css/agreement.css') }}">
<style>
    .mainScreen {
        background: url('{{ asset('assets/visitor_photos/remaining_screen_image.jpg') }}') no-repeat center center;
        background-size: cover;
    }
</style>
@endpush

@push('scripts')
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
@endpush
@endsection
