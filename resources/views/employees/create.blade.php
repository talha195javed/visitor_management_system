@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/employers_list">Employee</a></li>
                <li class="breadcrumb-item active">Register</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">

            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Register Employee</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">
                            <div class=" profile-edit pt-3" id="profile-edit">
                                <form id="employeeForm">
                                    @csrf

                                    <!-- Step 1: Full Name -->
                                    <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div id="nameField" class="form-group d-flex align-items-center msideb-3">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Full Name">
                                    </div>

                                    <!-- Step 2: Company -->
                                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                                    <div id="companyField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="company" id="company" placeholder="Office in which you are working">
                                    </div>

                                    <!-- Step 3: Email -->
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div id="emailField" class="form-group d-flex align-items-center mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                    </div>

                                    <!-- Step 4: Phone -->
                                    <label for="contact_number" class="col-md-4 col-lg-3 col-form-label">Contact Number</label>
                                    <div id="phoneField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number">
                                    </div>

                                    <!-- Step 6: ID Number -->
                                    <label for="position" class="col-md-4 col-lg-3 col-form-label">Position</label>
                                    <div id="idNumberField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="position" id="position" placeholder="Enter Your Company Position">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Register Employee</button>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: '/register-employee',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Employee Created Successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = '/home';
                            });
                        } else {
                            // Handle error (e.g., show an error message)
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an issue creating the employee.',
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX error
                        console.log(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error processing the request.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                });
            });
        });

    </script>
</main>


@endsection
