@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/employers_list">Employees</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <h2>{{ $employee->name }}</h2><br>
                        <h3>{{ $employee->position }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $employee->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $employee->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Position in Company</div>
                                    <div class="col-lg-9 col-md-8">{{ $employee->position }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Company</div>
                                    <div class="col-lg-9 col-md-8">{{ $employee->company ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Contact #</div>
                                    <div class="col-lg-9 col-md-8">{{ $employee->contact_number ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form id="editEmployeeForm">
                                    @csrf
                                    <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->id }}">

                                    <!-- Step 1: Full Name -->
                                    <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div id="nameField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $employee->name) }}" placeholder="Full Name">
                                    </div>

                                    <!-- Step 2: Company -->
                                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company Visited</label>
                                    <div id="companyField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="company" id="company" value="{{ old('company', $employee->company) }}" placeholder="Office you have to Visit">
                                    </div>

                                    <!-- Step 3: Email -->
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div id="emailField" class="form-group d-flex align-items-center mb-3">
                                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $employee->email) }}" placeholder="Email">
                                    </div>

                                    <!-- Step 3: Position -->
                                    <label for="position" class="col-md-4 col-lg-3 col-form-label">Position</label>
                                    <div id="position" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="position" id="position" value="{{ old('position', $employee->position) }}" placeholder="Position in Company">
                                    </div>

                                    <!-- Step 4: Phone -->
                                    <label for="contact_number" class="col-md-4 col-lg-3 col-form-label">Contact Number</label>
                                    <div id="phoneField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="contact_number" id="contact_number" value="{{ old('contact_number', $employee->contact_number) }}" placeholder="Contact Number">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
            $('#editEmployeeForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: '/update-employee',
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
                                location.reload();
                            });
                        } else {
                            // Handle error (e.g., show an error message)
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an issue updating the Employee.',
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
