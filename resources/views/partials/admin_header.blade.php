<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <!-- Mobile sidebar toggle button -->
        <button class="sidebar-mobile-toggler me-3 d-lg-none" type="button">
            <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('assets/img/admin_logo.png') }}" alt="Brand Logo" class="img-fluid" style="height: 50px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @endif
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/home">
                            <i class="bi bi-grid"></i> Dashboard
                        </a>
                        <a class="dropdown-item" href="{{ route('visitors.admin_list') }}">
                            <i class="bi bi-menu-button-wide"></i> Visitors
                        </a>
                        <a class="dropdown-item" href="{{ route('visitors.admin_pre_register') }}">
                            <i class="bi bi-circle"></i> Pre Register
                        </a>
                        <a class="dropdown-item" href="{{ route('visitors.admin_checked_in') }}">
                            <i class="bi bi-circle"></i> Checked In's List
                        </a>
                        <a class="dropdown-item" href="{{ route('visitors.admin_checked_out') }}">
                            <i class="bi bi-circle"></i> Checked Out's List
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person"></i> Profile
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-in-right"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
    .sidebar-mobile-toggler {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #2c3e50;
        padding: 0.25rem 0.5rem;
    }

    @media (min-width: 993px) {
        .sidebar-mobile-toggler {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggler = document.querySelector('.sidebar-mobile-toggler');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        const body = document.body;

        if (mobileToggler) {
            mobileToggler.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                if (overlay) overlay.classList.toggle('active');
                body.classList.toggle('no-scroll');
            });
        }
    });
</script>
