@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>System Fields Configuration</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Configuration</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.update_field_visibility') }}" method="POST">
        @csrf

        <div class="screen-cards-container">
            @foreach($screens as $screen)
            <div class="screen-card">
                <div class="card-header">
                    <label class="screen-label">
                        <!-- Hidden input for unchecked checkboxes -->
                        <input type="hidden" name="screens[{{ $screen->screen_name }}]" value="0">
                        @if($screen->screen_name == 'check_in')
                        <input type="checkbox" name="screens[{{ $screen->screen_name }}]" value="1" class="screen-checkbox" checked disabled>
                        <strong>{{ $screen->name }}</strong>
                        @else
                        <input type="checkbox" name="screens[{{ $screen->screen_name }}]" value="1" class="screen-checkbox" {{ $screen->is_visible ? 'checked' : '' }}>
                        <strong>{{ $screen->name }}</strong>
                        @endif
                    </label>
                </div>

                <div class="card-body">
                    <div class="fields-container">
                        @if(isset($fields[$screen->screen_name]))
                        @foreach($fields[$screen->screen_name] as $field)
                        <div class="field-item">
                            <!-- Hidden input for unchecked checkboxes -->
                            <input type="hidden" name="fields[{{ $field->field_name }}]" value="0">
                            <label>
                                <input type="checkbox" name="fields[{{ $field->field_name }}]" value="1"
                                       {{ $field->is_visible ? 'checked' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $field->field_name)) }}
                            </label>
                        </div>
                        @endforeach
                        @else
                        <p>No fields available for this screen.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn-save-settings">Save Settings</button>
    </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
</script>
<style>
    /* General Body */
    body {
        background-color: #f4f7fc;
        font-family: 'Roboto', sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Align items to the top */
        height: 100vh;
        box-sizing: border-box;
        padding-top: 40px; /* Adjust the top spacing to keep it the same as before */
    }

    /* Header Section */
    .pagetitle {
        text-align: center;
        margin-bottom: 40px;
    }

    .pagetitle h1 {
        font-size: 28px;
        font-weight: 600;
        color: #3f3f3f;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        font-size: 14px;
        color: #8f8f8f;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #8f8f8f;
    }

    /* Container for cards */
    .screen-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Card Design */
    .screen-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }


    .screen-card:hover {
        transform: translateY(-10px) scale(1.02);
    }

    /* Card Header */
    .card-header {
        background: #5c6bc0;
        color: #fff;
        padding: 20px;
        font-size: 18px;
        font-weight: 500;
        text-align: center;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .screen-label {
        display: flex;
        align-items: center;
        font-size: 16px;
    }

    .screen-checkbox {
        margin-right: 10px;
    }

    /* Card Body */
    .card-body {
        padding: 20px;
        background: #fafafa;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .fields-container {
        padding-left: 20px;
    }

    .field-item {
        margin: 15px 0;
        font-size: 16px;
        display: flex;
        align-items: center;
        transition: transform 0.2s ease;
    }

    .field-item label {
        color: #555;
    }

    .field-item label:hover {
        color: #3f51b5;
    }

    .field-item input {
        margin-right: 10px;
    }

    /* Save Button */
    .btn-save-settings {
        display: block;
        width: 100%;
        padding: 15px;
        font-size: 18px;
        color: #fff;
        background: linear-gradient(90deg, #42a5f5, #1e88e5);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.1s ease, box-shadow 0.2s ease;
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .btn-save-settings:hover {
        background: linear-gradient(90deg, #1e88e5, #42a5f5);
        box-shadow: 0px 0px 10px rgba(66, 165, 245, 0.6);
        transform: scale(1.05);
    }

    .btn-save-settings:active {
        transform: scale(0.98);
    }

    input[type="checkbox"] {
        width: 20px;
        height: 20px;
        border: 2px solid #5c6bc0;
        border-radius: 4px;
        display: inline-block;
        position: relative;
        transition: all 0.2s ease-in-out;
    }

    input[type="checkbox"]:checked {
        background-color: #5c6bc0;
        border-color: #3f51b5;
    }

    input[type="checkbox"]:checked::after {
        content: '✔';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
    }
    .field-item:hover {
        transform: scale(1.05);
        transition: all 0.2s ease-in-out;
    }

    input[type="checkbox"]:hover {
        cursor: pointer;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css">
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
</script>
@endsection
