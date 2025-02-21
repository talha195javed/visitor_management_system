@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/admin_login.css') }}">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        background: linear-gradient(#0b1c30, #0b1c30);
        height: 100%;
    }

    .login-box {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 400px;
        padding: 40px;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, .5);
        box-sizing: border-box;
        box-shadow: 0 15px 25px rgba(0, 0, 0, .6);
        border-radius: 10px;
        color: #fff;
    }

    .login-box h2 {
        margin: 0 0 30px;
        padding: 0;
        text-align: center;
    }

    .user-box {
        position: relative;
    }

    .user-box input {
        width: 100%;
        padding: 10px 0;
        font-size: 16px;
        color: #fff;
        margin-bottom: 30px;
        border: none;
        border-bottom: 1px solid #fff;
        outline: none;
        background: transparent;
    }

    .user-box label {
        position: absolute;
        top: 0;
        left: 0;
        padding: 10px 0;
        font-size: 16px;
        color: #fff;
        pointer-events: none;
        transition: .5s;
    }

    .user-box input:focus ~ label,
    .user-box input:valid ~ label {
        top: -20px;
        left: 0;
        color: #03e9f4;
        font-size: 12px;
    }

    button {
        background: transparent;
        border: none;
        color: #03e9f4;
        font-size: 16px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 4px;
        position: relative;
        display: inline-block;
    }

    button:hover {
        color: #fff;
    }

    button:focus {
        outline: none;
    }

</style>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="user-box">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            <label>Email Address</label>
            @error('email')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="user-box">
            <input id="password" type="password" name="password" required autocomplete="current-password">
            <label>Password</label>
            @error('password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6 offset-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
        </div>

        <button type="submit">Login</button>
    </form>
</div>
@endsection
