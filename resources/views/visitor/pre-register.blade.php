@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pre-Register Visitor</h2>
    <form action="{{ route('visitor.storePreRegister') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" name="full_name" id="full_name" required>
        </div>

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" class="form-control" name="company" id="company">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" name="phone" id="phone">
        </div>

        <button type="submit" class="btn btn-primary">Pre-Register</button>
    </form>
</div>
@endsection
