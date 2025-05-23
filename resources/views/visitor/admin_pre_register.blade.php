@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Visitor Pre-Registration</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Visitors</li>
                        <li class="breadcrumb-item active">Pre-Register</li>
                    </ol>
                </nav>
            </div>
            <div class="badge bg-primary text-white p-2">
                <i class="bi bi-person-plus-fill"></i> New Visitor
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-badge me-2"></i>Visitor Information
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="stepper-wrapper mb-4">
                            <div class="stepper-item completed">
                                <div class="step-counter">1</div>
                                <div class="step-name">Personal</div>
                            </div>
                            <div class="stepper-item active">
                                <div class="step-counter">2</div>
                                <div class="step-name">Contact</div>
                            </div>
                            <div class="stepper-item">
                                <div class="step-counter">3</div>
                                <div class="step-name">Verification</div>
                            </div>
                        </div>

                        <form id="editVisitorForm" class="needs-validation" novalidate>
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" required>
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid name.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="company" id="company" class="form-select" required>
                                            <option value="" selected disabled>Select company</option>
                                            <option value="ABC Corporation">ABC Corporation</option>
                                            <option value="XYZ Enterprises">XYZ Enterprises</option>
                                            <option value="Global Solutions">Global Solutions</option>
                                        </select>
                                        <label for="company" class="form-label">Company Visited</label>
                                        <div class="invalid-feedback">
                                            Please select a company.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid email.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone" required>
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid phone number.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="id_type" id="id_type" class="form-select" required>
                                            <option value="" selected disabled>Select ID Type</option>
                                            <option value="emirates_id">Emirates ID</option>
                                            <option value="passport">Passport</option>
                                            <option value="cnic">National CNIC</option>
                                        </select>
                                        <label for="id_type" class="form-label">Identification Type</label>
                                        <div class="invalid-feedback">
                                            Please select an ID type.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="identification_number" id="identification_number" placeholder="ID Number" required>
                                        <label for="identification_number" class="form-label">ID Number</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid ID number.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user() && (auth()->user()->role == 'superAdmin'))
                            <div class="form-group d-flex align-items-center mb-3">
                                <select class="form-control" name="client_id" id="client_id" required>
                                    <option value="">Select a Client</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-md-2">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Register Visitor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info mt-4" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    All visitors must present their physical ID upon arrival for verification.
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Form validation
            (function () {
                'use strict'

                var forms = document.querySelectorAll('.needs-validation')

                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()

            // Form submission
            $('#editVisitorForm').on('submit', function(e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    return;
                }

                var formData = $(this).serialize();
                var submitBtn = $(this).find('button[type="submit"]');
                var originalText = submitBtn.html();

                // Show loading state
                submitBtn.prop('disabled', true);
                submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

                $.ajax({
                    url: '/pre-registor-visitor',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Visitor pre-registered successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(function() {
                                window.location.href = '/home';
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'There was an issue pre-registering the visitor.',
                                icon: 'error',
                                confirmButtonText: 'Try Again',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error processing your request. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalText);
                    }
                });
            });

            // Phone number formatting
            $('#phone').inputmask('(999) 999-9999');
        });
    </script>

    <style>
        .card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }

        .stepper-wrapper::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            z-index: 2;
        }

        .stepper-item.completed .step-counter {
            background-color: #198754;
            color: white;
        }

        .stepper-item.active .step-counter {
            background-color: #0d6efd;
            color: white;
            border: 3px solid #b8d4ff;
        }

        .step-counter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .step-name {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .form-floating {
            position: relative;
        }

        .form-floating label {
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-2px);
        }
    </style>
</main>

@endsection
