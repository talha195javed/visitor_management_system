<style>
    .container {
        font-family: 'Poppins', sans-serif; /* You can change this to any beautiful font */
        color: #333; /* Dark grey color */
    }

    .nav-link {
        color: #333 !important;
        font-weight: 500;
        position: relative;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .nav-link:hover {
        color: #000 !important;
        transform: scale(1.1); /* Zoom effect on hover */
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #000;
        transform: scaleX(0);
        transform-origin: bottom right;
        transition: transform 0.3s ease;
    }

    .nav-link:hover::after {
        transform: scaleX(1);
        transform-origin: bottom left;
    }

    /* Active tab styles */
    .nav-link.active {
        color: #007bff !important; /* Blue color for active tab */
        font-weight: bold;
    }

    .nav-link.active::after {
        transform: scaleX(1);
        transform-origin: bottom left;
        background-color: #007bff; /* Blue underline for active tab */
    }

    /* Optional: Adding background color for active tab */
    .nav-link.active {
        background-color: #f0f8ff; /* Light background color for active tab */
        border-radius: 4px;
    }

    .lang-item a {
        color: #333 !important;
        font-weight: 500;
        position: relative;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .lang-item a:hover {
        color: #000 !important;
        transform: scale(1.1); /* Zoom effect on hover */
    }

    .lang-item a::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #000;
        transform: scaleX(0);
        transform-origin: bottom right;
        transition: transform 0.3s ease;
    }

    .lang-item a:hover::after {
        transform: scaleX(1);
        transform-origin: bottom left;
    }

    /* Active language item */
    .lang-item a.active {
        color: #007bff !important;
        background-color: #f0f8ff; /* Light background color for active language */
        font-weight: bold;
    }

</style>
<header id="header" class="header align-items-center fixed-top">
    <div id="navbar" class="shadow-sm">
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
                <a href="{{ route('/') }}" class="d-flex align-items-center mb-2 mb-md-0 text-dark text-decoration-none">
                    <img width="350" height="auto" src="assets/img/gobusiness.png" alt="iVisita - Visitor Management System">
                </a>
                <div class="menu-primary-navigation-english-container">
                    <ul id="menu-primary-navigation-english" class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 pe-0">
                        <li id="menu-item-37" class="nav-item menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-37">
                            <a href="{{ route('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                        </li>
                        <li id="menu-item-32" class="nav-item menu-item menu-item-type-post_type menu-item-object-page menu-item-32">
                            <a href="{{ route('services') }}" class="nav-link {{ request()->is('services') ? 'active' : '' }}">Services</a>
                        </li>
                        <li id="menu-item-33" class="nav-item menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-24 current_page_item menu-item-33">
                            <a href="{{ route('about') }}" aria-current="page" class="nav-link {{ request()->is('about') ? 'active' : '' }}">About</a>
                        </li>
                        <li id="menu-item-34" class="nav-item menu-item menu-item-type-post_type menu-item-object-page menu-item-34">
                            <a href="{{ route('contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
                        </li>
                    </ul></div>
                <div class="text-end">
                    <button class="lang-item lang-item-3 lang-item-ar lang-item-first" onclick="translateToArabic()">العربية</button>
                </div>
                <div id="google_translate_element" style="display: none;"></div>
            </header>
        </div>
    </div>

            <i class="mobile-nav-toggle d-xl-none bi bi-list d-none"></i>

</header>

<script>
    function openGoogleMaps() {
        window.open("https://www.google.com/maps?q=25.2671394,55.308155", "_blank");
    }
</script>

