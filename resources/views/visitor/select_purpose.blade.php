@extends('layouts.front_app')

@section('content')
<div id="mainScreen"
     class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">
    <div class="card p-4 shadow-lg rounded-4 border-0" style="max-width: 500px; background: #fff; transition: 0.3s;">

        <!-- Title -->
        <h2 class="text-center fw-bold mb-3">Select Purpose of Visit</h2>
        <p class="text-center text-muted">Please provide the details below:</p>
        @php
        use App\Models\FieldSetting;
        $visibleFields = FieldSetting::where('is_visible', true)->pluck('field_name')->toArray();
        @endphp
        <form action="{{ route('visitor.storePurpose' , $visitor->id) }}" method="POST" class="mt-3">
            @csrf

            <!-- Purpose Field -->
            @if(in_array('purpose', $visibleFields))
            <div class="mb-3">
                <label class="fw-semibold">Purpose of Visit</label>
                <input type="text" name="purpose" class="form-control input-field" placeholder="Enter purpose..."
                       required>
            </div>
            @endif
            <!-- Employee Selection -->
            @if(in_array('employee_to_visit', $visibleFields))
            <div class="mb-3">
                <label class="fw-semibold">Select Employee you have to Visit <br><br><span> (If not Confirmed please select HR)</span>
                    <br><br></label>
                <select name="employee_id" class="form-control input-field">
                    @foreach($employees->sortBy('name') as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }} ({{ $employee->position }})
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Proceed Button -->
            <button class="btn btn-success w-100 py-2 proceed-btn">
                ✅ Proceed
            </button>
        </form>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/select_purpose.css') }}">

@endpush
@endsection
