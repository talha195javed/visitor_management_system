@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/admin/users_list">Users</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <h2>{{ $user->name }}</h2><br>
                        <h3>{{ $user->email }}</h3>
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
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form id="editUserForm">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">

                                    <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div id="nameField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" placeholder="Full Name">
                                    </div>

                                    <!-- Step 2: Email -->
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div id="emailField" class="form-group d-flex align-items-center mb-3">
                                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="Email">
                                    </div>

                                    <!-- Step 3: Position -->
                                    <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                    <div id="passwordField" class="form-group d-flex align-items-center mb-3">
                                        <input type="password" class="form-control" name="password" id="password" value="" placeholder="New Password (if Required)">
                                    </div>

                                    <!-- Step 4: Phone -->
                                    <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirm Password</label>
                                    <div id="passwordConfirmationField" class="form-group d-flex align-items-center mb-3">
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="" placeholder="Confirm Password ">
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
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                let name = $('#name').val().trim();
                let email = $('#email').val().trim();
                let password = $('#password').val();
                let confirmPassword = $('#password_confirmation').val();

                // Basic validation
                if (name === '' || email === '') {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Full Name and Email are required.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Password match validation
                if (password !== '' && password !== confirmPassword) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Passwords do not match!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var formData = $(this).serialize();

                $.ajax({
                    url: '/update-user',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Profile updated successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an issue updating the profile.',
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
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
