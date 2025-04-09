@extends('layouts.front_app')

@section('content')
<div id="mainScreen" class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">    <div class="card p-4 shadow-lg rounded-4 border-0" style="max-width: 500px; background: #fff; transition: 0.3s;">

        <h2 class="fw-bold mb-3">Emergency Contact Details</h2>
        <p class="text-muted">Please Provide Emergency Contact Details</p>
    @php
    use App\Models\FieldSetting;
    $visibleFields = FieldSetting::where('is_visible', true)->pluck('field_name')->toArray();
    @endphp

        <form action="{{ route('visitor.storeEmergencyContact', $visitor->id) }}" method="POST" class="shadow-lg p-4 rounded-4 bg-white" style="width: 100%; max-width: 600px; max-height: 600px;">
            @csrf

            @foreach(['emergency_name' => 'Emergency Contact Name', 'emergency_phone' => 'Emergency Contact Phone', 'emergency_relation' => 'Relation to Visitor'] as $field => $placeholder)
            @if(in_array($field, $visibleFields))
            <div class="form-group d-flex align-items-center mb-3">
                <input type="text" class="form-control" name="{{ $field }}" id="{{ $field }}" placeholder="{{ $placeholder }}" value="{{ old($field) }}" required>
            </div>
            @endif
            @endforeach

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100 py-3">Save Emergency Contact</button>
            </div>
        </form>
</div>

@if ($errors->any())
<script>
    Swal.fire({
        title: "Missing Information",
        text: "Please fill in all the required fields.",
        icon: "error",
        confirmButtonText: "OK"
    });
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @push('styles')
<link rel="stylesheet" href="{{ asset('css/emergency_contact.css') }}">
    @endpush
@endsection
