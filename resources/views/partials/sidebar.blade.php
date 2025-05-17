<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3 class="sidebar-brand">Visitor<span>Pro</span></h3>
        <div class="sidebar-toggler">
            <i class="bi bi-list"></i>
        </div>
    </div>

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link" href="/home">
                <div class="nav-icon">
                    <i class="bi bi-grid"></i>
                </div>
                <span>Dashboard</span>
                <div class="nav-highlight"></div>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item has-submenu">
            <a class="nav-link" data-bs-target="#visitors-nav" data-bs-toggle="collapse" href="#">
                <div class="nav-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span>Visitors</span>
                <div class="submenu-arrow">
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-highlight"></div>
            </a>
            <ul id="visitors-nav" class="submenu collapse" data-bs-parent="#sidebar-nav">
                <li class="submenu-item">
                    <a href="{{ route('visitors.admin_list') }}" class="">
                        <i class="bi bi-record-circle"></i>
                        <span>Visitor List</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('visitors.admin_pre_register') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Pre Register</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('visitors.admin_checked_in') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Checked In's List</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('visitors.admin_checked_out') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Checked Out's List</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('visitors_archive_list') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Archive List</span>
                    </a>
                </li>
            </ul>
        </li>

        @if(auth()->user() && (auth()->user()->role == 'client' || auth()->user()->role == 'superAdmin'))
        <li class="nav-item has-submenu">
            <a class="nav-link collapsed" data-bs-target="#employees-nav" data-bs-toggle="collapse" href="#">
                <div class="nav-icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <span>Employees</span>
                <div class="submenu-arrow">
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-highlight"></div>
            </a>
            <ul id="employees-nav" class="submenu collapse" data-bs-parent="#sidebar-nav">
                <li class="submenu-item">
                    <a href="{{ route('employers_list') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>List</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('employers_archive_list') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Archive List</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->user() && (auth()->user()->role == 'admin' || auth()->user()->role == 'superAdmin'))
        <li class="nav-item has-submenu">
            <a class="nav-link collapsed" data-bs-target="#admin-nav" data-bs-toggle="collapse" href="#">
                <div class="nav-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <span>Admin</span>
                <div class="submenu-arrow">
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-highlight"></div>
            </a>
            <ul id="admin-nav" class="submenu collapse" data-bs-parent="#sidebar-nav">
                <li class="submenu-item">
                    <a href="{{ route('admin.users.list') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>User</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('admin.clients.list') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('admin.emails.list') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Email Logs</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('admin.field_visibility') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Configuration</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('admin.company_setting') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Company Settings</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a class="nav-link collapsed" data-bs-target="#payments-nav" data-bs-toggle="collapse" href="#">
                <div class="nav-icon">
                    <i class="bi bi-credit-card-2-front-fill"></i>
                </div>
                <span>Subscription</span>
                <div class="submenu-arrow">
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-highlight"></div>
            </a>
            <ul id="payments-nav" class="submenu collapse" data-bs-parent="#sidebar-nav">
                <li class="submenu-item">
                    <a href="{{ route('admin.clients.index') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="{{ route('admin.subscriptions.index') }}">
                        <i class="bi bi-record-circle"></i>
                        <span>All Subscriptions</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li class="nav-item logout-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div class="nav-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <span>Logout</span>
                <div class="nav-highlight"></div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="user-info">
                <span class="username">{{ auth()->user()->name }}</span>
                <span class="role">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
    </div>
</aside>

<div class="overlay"></div>

<style>

    .nav-highlight {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #4eacfd;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .nav-link:hover .nav-highlight,
    .nav-link.active .nav-highlight {
        opacity: 1;
    }

    .has-submenu .nav-link {
        justify-content: space-between;
    }

    .has-submenu .nav-link.collapsed .submenu-arrow {
        transform: rotate(0deg);
    }

    .has-submenu .nav-link:not(.collapsed) .submenu-arrow {
        transform: rotate(180deg);
        color: #4eacfd;
    }

    .submenu-item a {
        display: flex;
        align-items: center;
        padding: 8px 20px 8px 15px;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        position: relative;
    }

    .submenu-item a.active {
        color: #4eacfd;
        font-weight: 500;
    }

    .submenu-item a.active:before {
        content: "";
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4eacfd;
    }

    .submenu-item i {
        margin-right: 10px;
        font-size: 0.7rem;
        color: inherit;
    }

    .logout-item {
        margin-top: auto;
        border-top: 1px solid #e9ecef;
        padding-top: 10px;
    }

    .logout-item .nav-link {
        color: #6c757d;
    }

    .logout-item .nav-link:hover {
        color: #ff6b6b;
        background: rgba(255, 107, 107, 0.05);
    }

    .sidebar-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        background: #ffffff;
    }

    .user-profile {
        display: flex;
        align-items: center;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 1.2rem;
        color: #4eacfd;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .username {
        font-weight: 600;
        font-size: 0.9rem;
        color: #2c3e50;
    }

    .role {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Active State Management */
    .collapse.show {
        display: block;
    }

    .has-submenu .nav-link[aria-expanded="true"] {
        color: #4eacfd;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-toggler {
            display: block;
        }

        .sidebar.show {
            transform: translateX(0);
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.1);
        }
    }

    /* Animation for submenu items */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .submenu-item {
        animation: fadeIn 0.3s ease forwards;
    }

    .submenu-item:nth-child(1) { animation-delay: 0.05s; }
    .submenu-item:nth-child(2) { animation-delay: 0.1s; }
    .submenu-item:nth-child(3) { animation-delay: 0.15s; }
    .submenu-item:nth-child(4) { animation-delay: 0.2s; }
    .submenu-item:nth-child(5) { animation-delay: 0.25s; }

    .sidebar {
        width: 280px;
        background: #fff;
        border-right: 1px solid #e9ecef;
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        font-family: 'Segoe UI', sans-serif;
    }

    .sidebar-header {
        padding: 20px;
        background-color: #ffffff;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-brand {
        font-size: 1.6rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .sidebar-brand span {
        color: #4eacfd;
    }

    .sidebar-nav {
        padding: 20px 0;
        flex: 1;
        overflow-y: auto;
    }

    .nav-item {
        margin-bottom: 10px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        text-decoration: none;
        color: #495057;
        transition: 0.3s;
        border-left: 4px solid transparent;
        border-radius: 0 25px 25px 0;
    }

    .nav-link:hover {
        background: #f1f5f9;
        color: #4eacfd;
        border-left: 4px solid #4eacfd;
    }

    .nav-link.active {
        background: #eaf4ff;
        color: #4eacfd;
        font-weight: bold;
        border-left: 4px solid #4eacfd;
    }

    .nav-icon {
        margin-right: 15px;
        font-size: 1.2rem;
    }

    .submenu {
        padding-left: 25px;
    }

    .submenu-item {
        margin: 8px 0;
    }

    .submenu-item a {
        display: flex;
        align-items: center;
        padding: 8px 20px;
        text-decoration: none;
        color: #6c757d;
        font-size: 0.95rem;
    }

    .submenu-item a:hover {
        color: #4eacfd;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .submenu-arrow {
        font-size: 0.9rem;
        color: #adb5bd;
    }

    .sidebar-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        align-items: center;
    }

    .avatar {
        font-size: 1.8rem;
        margin-right: 10px;
        color: #4eacfd;
    }

    .username {
        font-weight: 600;
        display: block;
        color: #343a40;
    }

    .role {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .sidebar-toggler {
        display: none;
    }

         /* Existing sidebar styles */
     .sidebar {
         width: 280px;
         background: #fff;
         border-right: 1px solid #e9ecef;
         min-height: 100vh;
         position: fixed;
         top: 0;
         left: 0;
         display: flex;
         flex-direction: column;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.03);
         transition: all 0.3s ease;
         font-family: 'Segoe UI', sans-serif;
         z-index: 1001;
     }

    /* Add these new responsive styles */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.1);
        }

        .sidebar-toggler {
            display: block;
            cursor: pointer;
            font-size: 1.5rem;
            color: #2c3e50;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
        }

        .overlay.active {
            display: block;
        }
    }

    /* For desktop screens */
    @media (min-width: 993px) {
        .sidebar-toggler {
            display: none !important;
        }
    }

    /* Scroll lock when sidebar is open */
    .no-scroll {
        overflow: hidden;
    }

    /* Rest of your existing sidebar styles */
    /* ... (keep all your existing styles) ... */
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggler = document.querySelector('.sidebar-toggler');
        const overlay = document.querySelector('.overlay');
        const body = document.body;

        // Toggle sidebar on mobile
        if (sidebarToggler) {
            sidebarToggler.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('show');
                overlay.classList.toggle('active');
                body.classList.toggle('no-scroll');
            });
        }

        // Close sidebar when clicking on overlay
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('active');
                body.classList.remove('no-scroll');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992 && !sidebar.contains(e.target) {
                sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('active');
                body.classList.remove('no-scroll');
            }
        });

        // Keep submenus open if they contain active items
        const activeSubmenuItems = document.querySelectorAll('.submenu-item a.active');
        activeSubmenuItems.forEach(item => {
            const submenu = item.closest('.submenu');
            if (submenu) {
                submenu.classList.add('show');
                const parentLink = submenu.previousElementSibling;
                if (parentLink) {
                    parentLink.classList.remove('collapsed');
                    parentLink.setAttribute('aria-expanded', 'true');
                }
            }
        });

        // Auto-close sidebar on mobile when clicking a link
        const navLinks = document.querySelectorAll('.sidebar-nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('active');
                    body.classList.remove('no-scroll');
                }
            });
        });
    });
</script>
