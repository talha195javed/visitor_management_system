@extends('layouts.front_app')

@section('content')
<div id="mainScreen"
     class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100"
         style="background: url('path-to-your-dark-image.jpg') no-repeat center center; background-size: cover;">
        <div class="col-md-8 col-lg-6 d-flex justify-content-center align-items-center p-5">
            @php
            use App\Models\FieldSetting;
            $visibleFields = FieldSetting::where('is_visible', true)->pluck('field_name')->toArray();
            @endphp

            <form id="progressiveForm" action="{{ route('visitor.storeCheckIn') }}" method="POST"
                  enctype="multipart/form-data" class="bg-dark p-5 rounded-5 shadow-lg w-100">
                @csrf

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-white mb-4">Visitor Registration</h3>
                    <p class="text-white-50">Please choose your visitor type below.</p>
                </div>

                <!-- Selection Buttons -->
                <div id="visitorTypeSelection" class="text-center mb-4">
                    @if(in_array('pre_registor', $visibleFields))
                    <button type="button" id="preRegisteredBtn" class="btn visitor-btn btn-pre me-3">Pre-Registered
                        User
                    </button>
                    @endif
                    @if(in_array('new_visitor', $visibleFields))
                    <button type="button" id="newVisitorBtn" class="btn visitor-btn btn-new">New Visitor</button>
                    @endif
                </div>

                <!-- Main form area (initially hidden) -->
                <div id="visitorFormContainer" style="display: none;">
                    <!-- Pre-Registration Check -->
                    <div id="checkField" class="form-group mb-4 text-center" style="display: none;">
                        <input type="email"
                               class="form-control bg-transparent text-white border-light form-control-lg shadow-lg mb-2"
                               name="check_email" id="check_email" placeholder="Enter your Email">

                        <div class="d-flex justify-content-center mt-3">
                            <button type="button" id="checkEmailBtn" class="btn btn-light w-50 py-2 btn-lg shadow-lg">
                                <span id="checkEmailText">Check</span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" id="visitor_id" name="visitor_id" value="">

                    <!-- Form Fields -->
                    <div id="formFields" style="display: none;">
                        @if(in_array('full_name', $visibleFields))
                        <div class="form-group align-items-center mb-3">
                            <label class="mb-1">Full Name</label>
                            <input type="text"
                                   class="form-control bg-transparent text-white border-light form-control-lg shadow-lg"
                                   name="full_name" id="full_name" placeholder="Full Name" required>
                        </div>
                        @endif

                        @if(in_array('company', $visibleFields))
                        <div class="form-group align-items-center mb-3">
                            <label class="mb-1">Company you have to Visit</label>
                            <input type="text"
                                   class="form-control bg-transparent text-white border-light form-control-lg shadow-lg"
                                   name="company" id="company" placeholder="Office you have to Visit" required>
                        </div>
                        @endif

                        @if(in_array('email', $visibleFields))
                        <div class="form-group align-items-center mb-3">
                            <label class="mb-1">Email</label>
                            <input type="email"
                                   class="form-control bg-transparent text-white border-light form-control-lg shadow-lg"
                                   name="email" id="email" placeholder="Email" required>
                        </div>
                        @endif

                        @if(in_array('phone', $visibleFields))
                        <div class="form-group mb-3">
                            <label class="mb-1">Contact Number</label>
                            <div class="d-flex align-items-center">
                                <select name="country_code" class="form-control me-2" style="width: 24%;">
                                    @foreach($countries as $country_code)
                                    <option value="{{ $country_code['calling_code'] }}">
                                        (+{{ $country_code['calling_code'] }}) {{ $country_code['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="text"
                                       class="form-control bg-transparent text-white border-light form-control-lg shadow-lg"
                                       name="phone" id="phone" placeholder="Phone" required>
                            </div>
                        </div>

                        @endif

                        @if(in_array('id_type', $visibleFields))
                        <div class="form-group align-items-center mb-3">
                            <label class="mb-1">Identification Type</label>
                            <select name="id_type" id="id_type"
                                    class="form-control bg-transparent text-white border-light form-control-lg shadow-lg"
                                    required>
                                <option value="">Select ID Type</option>
                                <option value="emirates_id">Emirates ID</option>
                                <option value="passport">Passport</option>
                                <option value="cnic">National CNIC</option>
                            </select>
                        </div>
                        @endif

                        @if(in_array('identification_number', $visibleFields))
                        <div class="form-group align-items-center mb-4">
                            <label class="mb-1">Identification Number</label>
                            <input type="text"
                                   class="form-control bg-transparent text-white border-light form-control-lg shadow-lg"
                                   name="identification_number" id="identification_number" placeholder="Enter ID Number"
                                   required>
                        </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-light w-100 py-2 btn-lg shadow-lg">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->has('email'))
<script>
    Swal.fire({
        title: "Duplicate Email",
        text: "The email has already been taken.",
        icon: "error",
        confirmButtonText: "OK"
    });
</script>
@endif

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/checkin.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/checkin.js') }}"></script>
<script>
    const checkPreRegisteredRoute = "{{ route('visitor.checkPreRegistered') }}";
</script>
@endpush
@endsection
