@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="container mt-5">
        <div class="card shadow-lg p-5 rounded-lg border-light">
            <h2 class="mb-4 text-center text-primary font-weight-bold">Upload Screen Images & Update Company Info</h2>
            <form action="{{ route('company_info.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="company_name" class="form-label fw-bold text-muted">Company Name:</label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="company_name" name="company_name"
                               value="{{ old('company_name', $companyInfo->company_name ?? '') }}"
                               placeholder="Enter company name">
                    </div>
                    <div class="col-md-6">
                        <label for="company_email" class="form-label fw-bold text-muted">Company Email:</label>
                        <input type="email" class="form-control form-control-lg shadow-sm" id="company_email" name="company_email"
                               value="{{ old('company_email', $companyInfo->company_email ?? '') }}"
                               placeholder="Enter company email">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="hr_email" class="form-label fw-bold text-muted">HR Email:</label>
                    <input type="email" class="form-control form-control-lg shadow-sm" id="hr_email" name="hr_email"
                           value="{{ old('hr_email', $companyInfo->hr_email ?? '') }}"
                           placeholder="Enter HR email">
                </div>

                <div class="row mb-4">
                    @php
                    $images = [
                    'welcome_screen_image' => 'Welcome Screen Image',
                    'main_screen_image' => 'Main Screen Image',
                    'remaining_screen_image' => 'Remaining Screen Image'
                    ];
                    @endphp

                    @foreach($images as $field => $label)
                    <div class="col-md-4 mb-3 text-center">
                        <label for="{{ $field }}" class="form-label fw-bold text-muted">{{ $label }}:</label>
                        <input type="file" class="form-control form-control-lg shadow-sm" id="{{ $field }}" name="{{ $field }}"
                               accept="image/*" onchange="previewImage(event, '{{ $field }}_preview')">
                        <div class="mt-3">
                            @if(isset($companyInfo->$field))
                            <img id="{{ $field }}_preview"
                                 src="{{ asset('assets/visitor_photos/' . $companyInfo->$field) }}"
                                 class="img-thumbnail rounded-lg shadow-lg" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                            <img id="{{ $field }}_preview" src="#" class="img-thumbnail rounded-lg shadow-lg d-none"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <input type="hidden" id="company_id" name="company_id" value="{{ $companyInfo->id ?? '' }}">

                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-success px-5 py-3 rounded-pill shadow-sm">
                        <i class="fas fa-save"></i> Save & Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
<script>
    Swal.fire({
        title: 'Validation Error!',
        html: `
            <ul style="text-align: left;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `,
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

<script>
    function previewImage(event, previewId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
