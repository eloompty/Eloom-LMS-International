<style>
    .user-panel,
    .user-panel .info {
        white-space: inherit;
    }

    .brand-link {
        white-space: inherit;
    }
</style>

<aside class="main-sidebar sidebar-light-primary elevation-4 admin-enterprise-sidebar-light">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex">
        <img src="{{ asset(getLogo()) }}" alt="LMS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ getTitle() }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.user') }}" class="nav-link {{ (request()->is('admin/profile')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                @if (checkRole('course', 'view') == true)
                <li class="nav-item {{ (request()->is('admin/course*') || request()->is('admin/unregistered*')) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.course.menu') }}" class="nav-link {{ (request()->is('admin/course*') || request()->is('admin/unregistered*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            Courses
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('intake', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.intake.index') }}" class="nav-link {{ (request()->is('admin/intake*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            Intakes
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('student', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.student.index') }}" class="nav-link {{ (request()->is('admin/student*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Students
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('student', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.student.offer.index') }}" class="nav-link {{ (request()->is('admin/offer*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Enquiries
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('trainer', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.trainer.index') }}" class="nav-link {{ (request()->is('admin/trainer*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                            Faculty/Teachers
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('online_class_group', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.onlineclass.group.index') }}" class="nav-link {{ (request()->is('admin/onlineclass/group*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p>
                            Group Online Classes
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('classroom', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.classroom.index') }}" class="nav-link {{ (request()->is('admin/classroom*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Classrooms
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('university', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.university.index') }}" class="nav-link {{ (request()->is('admin/university*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                            Universities
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('resource', 'view') == true)
                <li class="nav-item {{ (request()->is('admin/resource*')) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.resource.menu') }}" class="nav-link {{ (request()->is('admin/resource*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            Resources
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('assignment', 'view') == true)
                <li class="nav-item {{ (request()->is('admin/assignment*') || request()->is('admin/submission*') || request()->is('admin/resubmission*')) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.assignment.menu') }}" class="nav-link {{ (request()->is('admin/assignment*') || request()->is('admin/submission*') || request()->is('admin/resubmission*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Assignments
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('agent', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.agent.index') }}" class="nav-link {{ (request()->is('admin/agent*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Agents
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('company', 'view') == true || checkRole('company_delivery_site', 'view') == true)
                <li class="nav-item {{ (request()->is('admin/company*')) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.company.menu') }}" class="nav-link {{ (request()->is('admin/company*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Company Settings
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('setting', 'view') == true || checkRole('user', 'view') == true || checkRole('role', 'view') == true || checkRole('offer_status', 'view') == true || checkRole('social_category', 'view') == true || checkRole('payment', 'view') == true || checkRole('country', 'view') == true || checkRole('condition', 'view') == true || checkRole('credit', 'view') == true || checkRole('database_backup', 'view' || checkRole('email', 'view') == true || checkRole('email_template', 'view') == true) || checkRole('email_user', 'view') == true || checkRole('template', 'view') == true || checkRole('document_type', 'view') == true || checkRole('student_document_type', 'view') == true || checkRole('lead', 'view') || checkRole('zoho_lead', 'view') || checkRole('fee_type', 'view') || checkRole('location', 'view') == true)
                <li class="nav-item {{ (request()->is('admin/setting*')) || (request()->is('admin/user*')) || (request()->is('admin/role*')) || (request()->is('admin/offer-status*')) || (request()->is('admin/social/category*')) || (request()->is('admin/payment*'))  || (request()->is('admin/country*')) || (request()->is('admin/condition*')) || (request()->is('admin/credit*')) || (request()->is('admin/backup*')) || (request()->is('admin/identifier*')) || (request()->is('admin/export*')) || (request()->is('admin/email*')) || (request()->is('admin/temp_email*')) || (request()->is('admin/send_email_user*')) || (request()->is('admin/template*')) || (request()->is('admin/document/type*')) || (request()->is('admin/document/student-type*')) || (request()->is('admin/import')) || (request()->is('admin/lead*')) || (request()->is('admin/zoho*')) || (request()->is('admin/fee-type*')) || (request()->is('admin/location*')) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.setting.menu') }}" class="nav-link {{ (request()->is('admin/setting*')) || (request()->is('admin/user*')) || (request()->is('admin/role*')) || (request()->is('admin/offer-status*')) || (request()->is('admin/social/category*')) || (request()->is('admin/payment*')) || (request()->is('admin/country*')) || (request()->is('admin/condition*')) || (request()->is('admin/credit*')) || (request()->is('admin/backup*')) || (request()->is('admin/identifier*')) || (request()->is('admin/export*')) || (request()->is('admin/email*')) || (request()->is('admin/temp_email*')) || (request()->is('admin/send_email_user*')) || (request()->is('admin/template*')) || (request()->is('admin/document/type*')) || (request()->is('admin/document/student-type*')) || (request()->is('admin/import')) || (request()->is('admin/lead*')) || (request()->is('admin/zoho*')) || (request()->is('admin/fee-type*')) || (request()->is('admin/location*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            System Settings
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('ticket', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.ticket.index') }}" class="nav-link {{ (request()->is('admin/ticket*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-ticket-alt"></i>
                        <p>
                           Tickets
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('chat', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.chat.index') }}" class="nav-link {{ (request()->is('admin/chat*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comment"></i>
                        <p>
                           Chats
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('marking_type', 'view') == true)
                <li class="nav-item">
                    <a href="{{ route('admin.marking-type.index') }}" class="nav-link {{ (request()->is('admin/marking-type*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check"></i>
                        <p>
                            Marking Types
                        </p>
                    </a>
                </li>
                @endif
                @if (checkRole('student_report', 'view') == true || checkRole('intake_report', 'view') == true || checkRole('agent_report', 'view') == true || checkRole('due_pays_report', 'view') == true || checkRole('fee_received_report', 'view') == true || checkRole('commission_report', 'view') == true || checkRole('course_completion_report', 'view') == true || checkRole('report_template', 'view') == true)
                <li class="nav-item {{ (request()->is('admin/report*')) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.report.menu') }}" class="nav-link {{ (request()->is('admin/report*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Reports
                        </p>
                    </a>
                </li>
                @endif
                {{-- Gradebook --}}
                <li class="nav-item {{ (request()->is('admin/gradebook*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->is('admin/gradebook*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Gradebook <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.gradebook.students') }}" class="nav-link {{ (request()->is('admin/gradebook') || request()->is('admin/gradebook/show*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Student Grades</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.gradebook.appeals') }}" class="nav-link {{ (request()->is('admin/gradebook/appeals*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Grade Appeals</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Certificates --}}
                <li class="nav-item {{ (request()->is('admin/certificate*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->is('admin/certificate*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-certificate"></i>
                        <p>Certificates <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.certificate.template.index') }}" class="nav-link {{ (request()->is('admin/certificate/template*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Templates</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.certificate.issued.index') }}" class="nav-link {{ (request()->is('admin/certificate/issued*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Issued Certificates</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Announcements --}}
                <li class="nav-item">
                    <a href="{{ route('admin.announcement.index') }}" class="nav-link {{ (request()->is('admin/announcement*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Announcements</p>
                    </a>
                </li>
                {{-- Events --}}
                <li class="nav-item">
                    <a href="{{ route('admin.event.index') }}" class="nav-link {{ (request()->is('admin/event*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Events</p>
                    </a>
                </li>
                {{-- Surveys --}}
                <li class="nav-item {{ (request()->is('admin/survey*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->is('admin/survey*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-poll"></i>
                        <p>Surveys <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.survey.template.index') }}" class="nav-link {{ (request()->is('admin/survey/template*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Templates</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.survey.instance.index') }}" class="nav-link {{ (request()->is('admin/survey/instance*') || request()->is('admin/survey/results*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dispatched Surveys</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Scholarships --}}
                @if(feeSetting('scholarship_module') == 'yes' && (checkRole('scholarship', 'view') || checkRole('scholarship_application', 'view')))
                <li class="nav-item {{ (request()->is('admin/scholarship*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->is('admin/scholarship*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>Scholarships <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(checkRole('scholarship', 'view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.scholarship.index') }}" class="nav-link {{ (request()->is('admin/scholarship') || request()->is('admin/scholarship/create') || request()->is('admin/scholarship/edit*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Programs</p>
                            </a>
                        </li>
                        @endif
                        @if(checkRole('scholarship_application', 'view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.scholarship.application.index') }}" class="nav-link {{ (request()->is('admin/scholarship/application*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Applications</p>
                            </a>
                        </li>
                        @endif
                        @if(checkRole('scholarship', 'view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.scholarship.discount.index') }}" class="nav-link {{ (request()->is('admin/scholarship/discount*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Revenue Impact</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                {{-- Alumni --}}
                <li class="nav-item {{ (request()->is('admin/alumni*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->is('admin/alumni*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Alumni <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.alumni.index') }}" class="nav-link {{ (request()->is('admin/alumni') || request()->is('admin/alumni/show*') || request()->is('admin/alumni/create*')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Alumni</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.alumni.directory') }}" class="nav-link {{ (request()->is('admin/alumni/directory')) ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Directory</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
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
