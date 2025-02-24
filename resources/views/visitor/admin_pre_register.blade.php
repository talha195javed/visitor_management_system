@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <br>
        <h1>Profile</h1>
        <br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Pre Register</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <br>
    <section class="section profile">
        <div class="row">

            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Pre Register User</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">
                            <div class=" profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form id="editVisitorForm">
                                    @csrf

                                    <!-- Step 1: Full Name -->
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div id="nameField" class="form-group d-flex align-items-center msideb-3">
                                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name">
                                    </div>

                                    <!-- Step 2: Company -->
                                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company Visited</label>
                                    <div id="companyField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="company" id="company" placeholder="Office you have to Visit">
                                    </div>

                                    <!-- Step 3: Email -->
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div id="emailField" class="form-group d-flex align-items-center mb-3">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                    </div>

                                    <!-- Step 4: Phone -->
                                    <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div id="phoneField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
                                    </div>

                                    <!-- Step 5: ID Type -->
                                    <label for="id_type" class="col-md-4 col-lg-3 col-form-label">ID Type</label>
                                    <div id="idField" class="form-group d-flex align-items-center mb-3">
                                        <select name="id_type" id="id_type" class="form-control">
                                            <option value="">Select ID Type</option>
                                            <option value="emirates_id">Emirates ID</option>
                                            <option value="passport">Passport</option>
                                            <option value="cnic">National CNIC</option>
                                        </select>
                                    </div>

                                    <!-- Step 6: ID Number -->
                                    <label for="identification_number" class="col-md-4 col-lg-3 col-form-label">ID Number</label>
                                    <div id="idNumberField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="identification_number" id="identification_number" placeholder="Enter ID Number">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Register Visitor</button>
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
            $('#editVisitorForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: '/pre-registor-visitor',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Visitor updated successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = '/home';
                            });
                        } else {
                            // Handle error (e.g., show an error message)
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an issue updating the visitor.',
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
