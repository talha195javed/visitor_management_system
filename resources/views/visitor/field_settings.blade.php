@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle" data-aos="fade-down">
        <h1><i class="fas fa-cog"></i> System Fields Configuration</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Configuration</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.update_field_visibility') }}" method="POST" data-aos="fade-up">
        @csrf

        <div class="screen-cards-container">
            @foreach($screens as $screen)
            <div class="screen-card" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card-header">
                    <div class="screen-toggle">
                        <!-- Hidden input for unchecked checkboxes -->
                        <input type="hidden" name="screens[{{ $screen->screen_name }}]" value="0">
                        @if($screen->screen_name == 'check_in')
                        <input type="checkbox" id="screen-{{ $screen->screen_name }}" name="screens[{{ $screen->screen_name }}]" value="1" class="screen-checkbox" checked disabled>
                        <label for="screen-{{ $screen->screen_name }}" class="toggle-switch">
                            <span class="toggle-track"></span>
                        </label>
                        <strong>{{ $screen->name }}</strong>
                        <span class="badge badge-required">Required</span>
                        @else
                        <input type="checkbox" id="screen-{{ $screen->screen_name }}" name="screens[{{ $screen->screen_name }}]" value="1" class="screen-checkbox" {{ $screen->is_visible ? 'checked' : '' }}>
                        <label for="screen-{{ $screen->screen_name }}" class="toggle-switch">
                            <span class="toggle-track"></span>
                        </label>
                        <strong>{{ $screen->name }}</strong>
                        @endif
                    </div>
                    <i class="screen-icon fas {{ $screen->screen_name == 'check_in' ? 'fa-sign-in-alt' :
                                             ($screen->screen_name == 'profile' ? 'fa-user' :
                                             ($screen->screen_name == 'dashboard' ? 'fa-tachometer-alt' : 'fa-window-maximize')) }}"></i>
                </div>

                <div class="card-body">
                    <div class="fields-header">
                        <span>Available Fields</span>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="field-search" placeholder="Search fields..." data-screen="{{ $screen->screen_name }}">
                        </div>
                    </div>

                    <div class="fields-container">
                        @if(isset($fields[$screen->screen_name]))
                        @foreach($fields[$screen->screen_name] as $field)
                        <div class="field-item">
                            <!-- Hidden input for unchecked checkboxes -->
                            <input type="hidden" name="fields[{{ $field->field_name }}]" value="0">
                            <input type="checkbox" id="field-{{ $field->field_name }}" name="fields[{{ $field->field_name }}]" value="1"
                                   {{ $field->is_visible ? 'checked' : '' }}>
                            <label for="field-{{ $field->field_name }}" class="custom-checkbox">
                                <span class="checkmark"></span>
                                <span class="field-name">{{ ucwords(str_replace('_', ' ', $field->field_name)) }}</span>
                                <span class="field-preview">
                                    <i class="fas fa-eye{{ $field->is_visible ? '' : '-slash' }}"></i>
                                </span>
                            </label>
                        </div>
                        @endforeach
                        @else
                        <div class="no-fields">
                            <i class="far fa-folder-open"></i>
                            <p>No fields available for this screen</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="form-actions" data-aos="fade-up" data-aos-delay="300">
            <button type="submit" class="btn-save-settings">
                <i class="fas fa-save"></i> Save Configuration
            </button>
            <button type="reset" class="btn-reset">
                <i class="fas fa-undo"></i> Reset Changes
            </button>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css">
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });

    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'animated tada'
            }
        });
    @endif

    // Field search functionality
    document.querySelectorAll('.field-search').forEach(searchBox => {
        searchBox.addEventListener('input', function() {
            const screen = this.dataset.screen;
            const searchTerm = this.value.toLowerCase();
            const fieldItems = document.querySelectorAll(`.screen-card[data-screen="${screen}"] .field-item`);

            fieldItems.forEach(item => {
                const fieldName = item.querySelector('.field-name').textContent.toLowerCase();
                if (fieldName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Toggle all fields for a screen
    document.querySelectorAll('.screen-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const screenCard = this.closest('.screen-card');
            const fieldCheckboxes = screenCard.querySelectorAll('.field-item input[type="checkbox"]:not([disabled])');

            fieldCheckboxes.forEach(fieldCheckbox => {
                fieldCheckbox.checked = this.checked;
                const eyeIcon = fieldCheckbox.closest('.field-item').querySelector('.fa-eye, .fa-eye-slash');
                if (eyeIcon) {
                    eyeIcon.classList.toggle('fa-eye', this.checked);
                    eyeIcon.classList.toggle('fa-eye-slash', !this.checked);
                }
            });
        });
    });
</script>

<style>
    /* Base Styles */
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --dark-color: #2b2d42;
        --light-color: #f8f9fa;
        --success-color: #4cc9f0;
        --warning-color: #f8961e;
        --danger-color: #f72585;
        --border-radius: 12px;
        --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
        background-color: #f5f7ff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--dark-color);
        line-height: 1.6;
    }

    /* Header Section */
    .pagetitle {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .pagetitle h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .pagetitle h1 i {
        color: var(--primary-color);
    }

    .breadcrumb {
        background: none;
        padding: 0;
        justify-content: center;
        font-size: 0.9rem;
    }

    .breadcrumb-item a {
        color: var(--accent-color);
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .breadcrumb-item a:hover {
        color: var(--secondary-color);
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Cards Container */
    .screen-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* Card Design */
    .screen-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        overflow: hidden;
        border: none;
        position: relative;
        transform: translateY(0);
    }

    .screen-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    /* Card Header */
    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .screen-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .screen-checkbox {
        position: absolute;
        opacity: 0;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
        cursor: pointer;
    }

    .toggle-track {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 34px;
        transition: var(--transition);
    }

    .toggle-track:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: var(--transition);
    }

    .screen-checkbox:checked + .toggle-switch .toggle-track {
        background-color: rgba(255, 255, 255, 0.7);
    }

    .screen-checkbox:checked + .toggle-switch .toggle-track:before {
        transform: translateX(26px);
    }

    .card-header strong {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .badge-required {
        background-color: var(--danger-color);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-size: 0.7rem;
        margin-left: 0.5rem;
        font-weight: 500;
    }

    .screen-icon {
        font-size: 1.5rem;
        opacity: 0.8;
    }

    /* Card Body */
    .card-body {
        padding: 1.5rem;
        background: white;
    }

    .fields-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .fields-header span {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.95rem;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box i {
        position: absolute;
        left: 10px;
        color: #adb5bd;
    }

    .field-search {
        padding: 0.5rem 1rem 0.5rem 2rem;
        border: 1px solid #e9ecef;
        border-radius: 30px;
        font-size: 0.85rem;
        transition: var(--transition);
        width: 160px;
    }

    .field-search:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.2);
        width: 180px;
    }

    /* Fields List */
    .fields-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .fields-container::-webkit-scrollbar {
        width: 6px;
    }

    .fields-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .fields-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .fields-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .field-item {
        margin: 0.75rem 0;
        position: relative;
    }

    .field-item input[type="checkbox"] {
        position: absolute;
        opacity: 0;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    .custom-checkbox:hover {
        background-color: #e9ecef;
        transform: translateX(5px);
    }

    .checkmark {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #ced4da;
        border-radius: 4px;
        margin-right: 0.75rem;
        position: relative;
        transition: var(--transition);
    }

    .field-item input[type="checkbox"]:checked ~ .custom-checkbox .checkmark {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .field-item input[type="checkbox"]:checked ~ .custom-checkbox .checkmark:after {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0.75rem;
    }

    .field-name {
        flex-grow: 1;
        font-size: 0.95rem;
        color: #495057;
    }

    .field-preview {
        color: #adb5bd;
        transition: var(--transition);
    }

    .field-item input[type="checkbox"]:checked ~ .custom-checkbox .field-preview {
        color: var(--success-color);
    }

    .no-fields {
        text-align: center;
        padding: 2rem 1rem;
        color: #adb5bd;
    }

    .no-fields i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    .no-fields p {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2.5rem;
        padding: 0 1rem;
    }

    .btn-save-settings {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-save-settings:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-save-settings:active {
        transform: translateY(0);
    }

    .btn-reset {
        background: white;
        color: var(--dark-color);
        border: 1px solid #dee2e6;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .screen-cards-container {
            grid-template-columns: 1fr;
        }

        .fields-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .field-search {
            width: 100%;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-save-settings, .btn-reset {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .screen-card {
        animation: fadeIn 0.5s ease-out forwards;
        opacity: 0;
    }

    .screen-card:nth-child(1) { animation-delay: 0.1s; }
    .screen-card:nth-child(2) { animation-delay: 0.2s; }
    .screen-card:nth-child(3) { animation-delay: 0.3s; }
    .screen-card:nth-child(4) { animation-delay: 0.4s; }
    .screen-card:nth-child(5) { animation-delay: 0.5s; }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
