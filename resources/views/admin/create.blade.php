@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <br>
        <h1>Profile</h1>
        <br>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/employers_list">Employee</a></li>
                <li class="breadcrumb-item active">Register</li>
            </ol>
        </nav>
    </div>
    <br>
    <section class="section profile">
        <div class="row">

            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Register
                                    User
                                </button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">
                            <div class=" profile-edit pt-3" id="profile-edit">
                                <form action="{{ route('admin.users.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select id="role" name="role" class="form-control" required>
                                            <option value="">Select Role</option>
                                            <option value="admin">Super Admin</option>
<!--                                            <option value="manager">Manager</option>-->
                                            <option value="client">Client</option>
                                        </select>
                                    </div>

                                    {{-- These fields will only be visible when role = client --}}
                                    <div id="package-fields" style="display: none;">
                                        <div class="form-group">
                                            <label for="package_start_date">Package Start Date</label>
                                            <input type="date" id="package_start_date" name="package_start_date" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="package_end_date">Package End Date</label>
                                            <input type="date" id="package_end_date" name="package_end_date" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="package_type">Package Type</label>
                                            <select id="package_type" name="package_type" class="form-control">
                                                <option value="">Select Package</option>
                                                <option value="basic">Basic</option>
                                                <option value="professional">Professional</option>
                                                <option value="enterprise">Enterprise</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Create User</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const roleSelect = document.getElementById('role');
        const packageFields = document.getElementById('package-fields');

        function togglePackageFields() {
            if (roleSelect.value === 'client') {
                packageFields.style.display = 'block';
            } else {
                packageFields.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', togglePackageFields);
        togglePackageFields(); // Initialize on page load
    });
</script>

@endsection

