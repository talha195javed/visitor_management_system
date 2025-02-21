@extends('layouts.front_app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg rounded-4 border-0" style="max-width: 400px; background: #fff; transition: 0.3s;">

        <!-- Title -->
        <h2 class="text-center fw-bold mb-3">Select Your Role</h2>
        <p class="text-center text-muted">Please select one of the following options:</p>

        <form method="POST" action="{{ route('visitor.setRole', $visitor->id) }}" class="mt-3">
            <input type="hidden" id="visitor_id" value="{{ $visitor->id }}">
            @csrf

            <!-- Buttons with Hover Effects -->
            <button type="submit" name="role" value="visitor" class="btn btn-primary w-100 py-2 mb-3 role-btn">
                🚀 Visitor
            </button>
            <button type="submit" name="role" value="client" class="btn btn-success w-100 py-2 mb-3 role-btn">
                💼 Client
            </button>
            <button type="submit" name="role" value="interviewer" class="btn btn-warning w-100 py-2 role-btn">
                🎤 Interviewer
            </button>
        </form>
    </div>
</div>

<!-- Custom CSS for Animation -->
<style>
    body {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        font-family: 'Poppins', sans-serif;
    }

    .card {
        animation: fadeInUp 0.8s ease-in-out;
    }

    .role-btn {
        font-size: 18px;
        font-weight: 600;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }

    .role-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.15);
    }

    .role-btn:active {
        transform: translateY(1px);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
