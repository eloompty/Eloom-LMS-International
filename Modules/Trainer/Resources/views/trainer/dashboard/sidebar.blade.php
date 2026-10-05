<style>
    .user-panel,
    .user-panel .info {
        white-space: inherit;
    }

    .brand-link {
        white-space: inherit;
    }
</style>

@php $sidebarTheme = getSiteTheme(); @endphp
<aside class="main-sidebar elevation-4 {{ $sidebarTheme == 'theme3' ? 'admin-enterprise-sidebar-light' : 'sidebar-dark-primary' }}">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex">
        <img src="{{ asset(getLogo()) }}" alt="LMS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ getTitle() }}</span>
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
                    <a href="{{ route('trainer.dashboard') }}" class="nav-link {{ (request()->is('trainer/dashboard')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.profile') }}" class="nav-link {{ (request()->is('trainer/profile')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.course.index') }}" class="nav-link {{ (request()->is('trainer/course*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Courses
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.students.index') }}" class="nav-link {{ (request()->is('trainer/students*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Students
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.assignments.index') }}" class="nav-link {{ (request()->is('trainer/assignments*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Assignments
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.submissions.index') }}" class="nav-link {{ (request()->is('trainer/submissions*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Submissions
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.gradebook.index') }}" class="nav-link {{ (request()->is('trainer/gradebook*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Gradebook</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.announcement.index') }}" class="nav-link {{ (request()->is('trainer/announcement*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Announcements</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.event.index') }}" class="nav-link {{ (request()->is('trainer/event*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Events</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.survey.template.index') }}" class="nav-link {{ (request()->is('trainer/survey*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-poll"></i>
                        <p>Surveys</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.calendar.index') }}" class="nav-link {{ (request()->is('trainer/calendar*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.onlineclass.group.index') }}" class="nav-link {{ (request()->is('trainer/onlineclass/group*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p>
                            Group Online Class
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.change.password') }}" class="nav-link {{ (request()->is('trainer/change/password')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainer.logout') }}" class="nav-link">
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