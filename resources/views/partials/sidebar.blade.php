<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="/home">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#visitors-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Visitors</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="visitors-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('visitors.admin_list') }}">
                        <i class="bi bi-circle"></i><span>Visitor List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visitors.admin_pre_register') }}">
                        <i class="bi bi-circle"></i><span>Pre Register</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visitors.admin_checked_in') }}">
                        <i class="bi bi-circle"></i><span>Checked In's List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visitors.admin_checked_out') }}">
                        <i class="bi bi-circle"></i><span>Checked Out's List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visitors_archive_list') }}">
                        <i class="bi bi-circle"></i><span>Archive List</span>
                    </a>
                </li>
            </ul>

        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#employees-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Employees</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="employees-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('employers_list') }}">
                        <i class="bi bi-circle"></i><span>List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('employers_archive_list') }}">
                        <i class="bi bi-circle"></i><span>Archive List</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#admin-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Admin</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="admin-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.users.list') }}">
                        <i class="bi bi-circle"></i><span>User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.emails.list') }}">
                        <i class="bi bi-circle"></i><span>Email Logs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.field_visibility') }}">
                        <i class="bi bi-circle"></i><span>Configuration</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.company_setting') }}">
                        <i class="bi bi-circle"></i><span>Company Settings</span>
                    </a>
                </li>
            </ul>
        </li>


        <li class="nav-item">
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-in-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li><!-- End Logout Nav -->
    </ul>
</aside>
