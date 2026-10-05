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
    <a href="{{ route('agent.dashboard') }}" class="brand-link d-flex">
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
                    <a href="{{ route('agent.dashboard') }}" class="nav-link {{ (request()->is('agent/dashboard')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.branch.index') }}" class="nav-link {{ (request()->is('agent/branch*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-code-branch"></i>
                        <p>
                            Branches
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.user.index') }}" class="nav-link {{ (request()->is('agent/user*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.student.enrolled.index') }}" class="nav-link {{ (request()->is('agent/enrolled-student*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Enrolled Students
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.student.index') }}" class="nav-link {{ (request()->is('agent/student*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Offer Students
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.commission.index') }}" class="nav-link {{ (request()->is('agent/commission*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Commissions
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.profile') }}" class="nav-link {{ (request()->is('agent/profile')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.change.password') }}" class="nav-link {{ (request()->is('agent/change/password')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('agent.logout') }}" class="nav-link">
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