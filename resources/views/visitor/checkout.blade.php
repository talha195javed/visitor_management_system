@extends('layouts.front_app')

@section('content')
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="mb-4">Visitor Check-Out</h1>
        <p>Select your name to check out.</p>
        <form action="{{ route('visitor.storeCheckOut') }}" method="POST">
            @csrf
            <select name="visitor_id" class="form-control mb-3" required>
                @foreach($visitors as $visitor)
                <option value="{{ $visitor->id }}">{{ $visitor->full_name }}</option>
                @endforeach
            </select>
            <button class="btn btn-danger btn-lg">Check Out</button>
        </form>
    </div>
</div>
@endsection
