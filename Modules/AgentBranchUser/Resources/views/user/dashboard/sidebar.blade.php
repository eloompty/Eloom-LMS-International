<style>
    .user-panel,
    .user-panel .info {
        white-space: inherit;
    }

    .brand-link {
        white-space: inherit;
    }
</style>

<!-- <aside class="main-sidebar sidebar-dark-primary elevation-4"> -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('branch-user.dashboard') }}" class="brand-link d-flex">
        <img src="{{ asset(getLogo()) }}" alt="LMS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" style="white-space: initial;">{{ getTitle() }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <br>
        <br>
        
        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('branch-user.dashboard') }}" class="nav-link {{ (request()->is('branch-user/dashboard')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch-user.profile') }}" class="nav-link {{ (request()->is('branch-user/profile')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch-user.student.enrolled.index') }}" class="nav-link {{ (request()->is('branch-user/enrolled-student')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Enrolled Students
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch-user.student.index') }}" class="nav-link {{ (request()->is('branch-user/student*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Offer Students
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch-user.commission.index') }}" class="nav-link {{ (request()->is('branch-user/commission*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Commissions
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch-user.change.password') }}" class="nav-link {{ (request()->is('branch-user/change/password')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch-user.logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>