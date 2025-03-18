@extends('layouts.front_app')

@section('content')
<div id="mainScreen" class="container-fluid d-flex flex-column flex-md-row justify-content-center align-items-center vh-100 ps-0">    <div class="card p-4 shadow-lg rounded-4 border-0" style="max-width: 500px; background: #fff; transition: 0.3s;">

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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const instructionText = document.getElementById("instructionText");
        const instructionMessages = {
            emergency_name: "Enter the name of the emergency contact and press the tick ✔",
            emergency_phone: "Enter the emergency contact's phone number and press the tick ✔",
            emergency_relation: "Enter the relation of the emergency contact to the visitor and press the tick ✔"
        };

        document.querySelectorAll("input").forEach(input => {
            input.addEventListener("focus", function () {
                if (instructionMessages[this.id]) {
                    instructionText.textContent = instructionMessages[this.id];
                }
            });
        });
    });
</script>

<style>
    body {
        background: linear-gradient(to right, #c6dbed, #00f2fe);
        font-family: 'Poppins', sans-serif;
    }
    .bg-custom-gradient { background: linear-gradient(135deg, #f06, #48c6ef, #6f86d6); }
    .hidden { display: none; }
    .fade-in { animation: fadeIn 0.8s ease-in-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    #mainScreen {
        background: url('{{ asset('assets/img/checkin6.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
        color: #fff;
    }
    .navbar-hidden {
        display: none !important; /* Hide the navbar */
    }
</style>
@endsection
