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
                    <a href="{{ route('student.dashboard') }}" class="nav-link {{ (request()->is('student/dashboard')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.profile') }}" class="nav-link {{ (request()->is('student/profile')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.course.index') }}" class="nav-link {{ (request()->is('student/course*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Courses
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.onlineclass.group.index') }}" class="nav-link {{ (request()->is('student/onlineclass/group*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p>
                            Group Online Classes
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.calendar.index') }}" class="nav-link {{ (request()->is('student/calendar*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.gradebook.courses') }}" class="nav-link {{ (request()->is('student/gradebook*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>My Grades</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.announcement.index') }}" class="nav-link {{ (request()->is('student/announcement*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Announcements</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.event.index') }}" class="nav-link {{ (request()->is('student/event*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Events</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.survey.index') }}" class="nav-link {{ (request()->is('student/survey*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-poll"></i>
                        <p>Surveys</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.certificate.index') }}" class="nav-link {{ (request()->is('student/certificate*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-certificate"></i>
                        <p>Certificates</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.alumni.profile') }}" class="nav-link {{ (request()->is('student/alumni*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Alumni Profile</p>
                    </a>
                </li>
                @if(feeSetting('fee_module')=='yes')
                <li class="nav-item">
                    <a href="{{ route('student.fee.index') }}" class="nav-link {{ (request()->is('student/fee*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Fees
                        </p>
                    </a>
                </li>
                @endif
                @if (feeSetting('unit_wise_fee') == 'yes')
                <li class="nav-item">
                    <a href="{{ route('student.fee.unit.index') }}" class="nav-link {{ (request()->is('student/unit-fee*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Unit Fees
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('student.notification.index') }}" class="nav-link {{ (request()->is('student/notification*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>
                            Notifications
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.chat.teacher.index') }}" class="nav-link {{ (request()->is('student/chat*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comment"></i>
                        <p>
                            Chat with Teachers
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.ticket.index') }}" class="nav-link {{ (request()->is('student/ticket*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-ticket-alt"></i>
                        <p>
                            Support Tickets
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.change.password') }}" class="nav-link {{ (request()->is('student/change/password')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            Change Password
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.logout') }}" class="nav-link">
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
