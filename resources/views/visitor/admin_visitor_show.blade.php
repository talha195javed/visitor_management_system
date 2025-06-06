@extends('layouts.app')

@section('content')
<style>
    /* Custom Modal Styling */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        border: none;
    }

    .image-container {
        background-color: #f8f9fa;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 300px;
    }

    .zoom-in-image {
        transition: transform 0.3s ease;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .modal.show .zoom-in-image {
        transform: scale(1.03);
    }

    .btn-close-white {
        filter: invert(1) brightness(100%);
    }

    .pattern-dots-md {
        background-image: radial-gradient(currentColor 1px, transparent 1px);
        background-size: 12px 12px;
        color: rgba(255, 255, 255, 0.3);
        height: 100%;
    }
</style>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/visitors/admin_list">Visitors</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <!-- Add cursor-pointer class and data-bs-toggle/data-bs-target attributes -->
                        <img src="{{ $photoPath }}" alt="Profile" class="cursor-pointer"
                             data-bs-toggle="modal" data-bs-target="#imageModal" style="cursor: pointer;">
                        <h2>{{ $visitor->full_name }}</h2>
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
                                    <div class="col-lg-9 col-md-8">{{ $visitor->full_name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">
                                        @if($visitor->email)
                                        <a href="mailto:{{ $visitor->email }}">{{ $visitor->email }}</a>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Company</div>
                                    <div class="col-lg-9 col-md-8">{{ $visitor->company ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Contact #</div>
                                    <div class="col-lg-9 col-md-8">+ {{ $visitor->country_code}} {{ $visitor->phone ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Check-in Time</div>
                                    <div class="col-lg-9 col-md-8">{{ $visitor->check_in_time ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Check-out Time</div>
                                    <div class="col-lg-9 col-md-8">{{ $visitor->check_out_time ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Status</div>
                                    <div class="col-lg-9 col-md-8">@if($visitor->check_out_time == '')
                                        {{ 'Checked In' }}
                                        @else
                                        {{ 'Checked Out' }}
                                        @endif</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Emergency Contact Person</div>
                                    <div class="col-lg-9 col-md-8">{{ $visitor->check_out_time ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Emergency Contact #</div>
                                    <div class="col-lg-9 col-md-8">{{ $visitor->check_out_time ?? 'N/A' }}</div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form id="editVisitorForm">
                                    @csrf
                                    <input type="hidden" id="visitor_id" name="visitor_id" value="{{ $visitor->id }}">

                                    <!-- Step 1: Full Name -->
                                    <label for="full_name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div id="nameField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="full_name" id="full_name" value="{{ old('full_name', $visitor->full_name) }}" placeholder="Full Name">
                                    </div>

                                    <!-- Step 2: Company -->
                                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company Visited</label>
                                    <div id="companyField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="company" id="company" value="{{ old('company', $visitor->company) }}" placeholder="Office you have to Visit">
                                    </div>

                                    <!-- Step 3: Email -->
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div id="emailField" class="form-group d-flex align-items-center mb-3">
                                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $visitor->email) }}" placeholder="Email">
                                    </div>

                                    <!-- Step 4: Phone -->
                                    <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div id="phoneField" class="form-group d-flex align-items-center mb-3">
                                        <select name="country_code" class="form-control" style="width: 11% !important;">
                                            @foreach($countries as $country_code)
                                            <option value="{{ $country_code['calling_code'] }}"
                                                    @if(old('country_code', $visitor->country_code) == $country_code['calling_code'] || ($visitor->country_code == null && $country_code['calling_code'] == 971)) selected @endif>
                                            (+{{ $country_code['calling_code'] }}) {{ $country_code['name'] }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', $visitor->phone) }}" placeholder="Phone">
                                    </div>


                                    <!-- Step 5: ID Type -->
                                    <label for="id_type" class="col-md-4 col-lg-3 col-form-label">ID Type</label>
                                    <div id="idField" class="form-group d-flex align-items-center mb-3">
                                        <select name="id_type" id="id_type" class="form-control">
                                            <option value="">Select ID Type</option>
                                            <option value="emirates_id" {{ old('id_type', $visitor->id_type) == 'emirates_id' ? 'selected' : '' }}>Emirates ID</option>
                                            <option value="passport" {{ old('id_type', $visitor->id_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                                            <option value="cnic" {{ old('id_type', $visitor->id_type) == 'cnic' ? 'selected' : '' }}>National CNIC</option>
                                        </select>
                                    </div>

                                    <!-- Step 6: ID Number -->
                                    <label for="identification_number" class="col-md-4 col-lg-3 col-form-label">ID Number</label>
                                    <div id="idNumberField" class="form-group d-flex align-items-center mb-3">
                                        <input type="text" class="form-control" name="identification_number" id="identification_number" value="{{ old('identification_number', $visitor->identification_number) }}" placeholder="Enter ID Number">
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
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 overflow-hidden">
                    <!-- Modal Header with Gradient Background -->
                    <div class="modal-header bg-gradient-primary text-white border-0 position-relative">
                        <h5 class="modal-title font-weight-bold">{{ $visitor->full_name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        <!-- Decorative Elements -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10">
                            <div class="pattern-dots-md"></div>
                        </div>
                    </div>

                    <!-- Modal Body with Animated Image -->
                    <div class="modal-body p-0 position-relative">
                        <div class="image-container">
                            <img src="{{ $photoPath }}" alt="Profile" class="img-fluid zoom-in-image"
                                 style="max-height: 70vh; width: auto; display: block; margin: 0 auto;">
                        </div>
                        <!-- Floating Info Badge -->
                        <div class="position-absolute bottom-0 start-0 m-3">
                    <span class="badge bg-info shadow-sm">
                        <i class="bi bi-person-badge me-1"></i> Visitor ID: {{ $visitor->id }}
                    </span>
                        </div>
                    </div>

                    <!-- Modal Footer with Action Buttons -->
                    <div class="modal-footer bg-light border-0 d-flex justify-content-between">
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="bi bi-download me-1"></i> Download
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i> Close
                            </button>
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
                    url: '/update-visitor',
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

        document.addEventListener('DOMContentLoaded', function() {
            // Add animation class when modal opens
            $('#imageModal').on('show.bs.modal', function () {
                $('.zoom-in-image').css('transform', 'scale(0.95)');
            });

            $('#imageModal').on('shown.bs.modal', function () {
                $('.zoom-in-image').css('transform', 'scale(1)');
            });

            // Download button functionality
            $(document).on('click', '.btn-outline-primary', function() {
                const link = document.createElement('a');
                link.href = '{{ $photoPath }}';
                link.download = 'visitor-{{ $visitor->id }}-profile.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });
    </script>
</main>


@endsection
