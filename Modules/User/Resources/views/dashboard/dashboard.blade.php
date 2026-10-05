@extends('user::layouts.master')
@section('title', 'Admin | Dashboard')
@php $userTheme = Auth::guard('user')->user()->theme ?? 'theme2'; @endphp

@section('content')
@php $hasCountryChartData = count($student_counts) > 0 && array_sum($student_counts) > 0; @endphp
@if ($userTheme != 'theme3')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-clipboard"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Courses</span>
                        <a href="{{ route('admin.course.index') }}">
                            <span class="info-box-number">
                                {{ $total_courses }}
                                <small></small>
                            </span>
                        </a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user-friends"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Students</span>
                        <a href="{{ route('admin.student.index') }}"><span class="info-box-number">{{ $total_students }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-friends"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Faculty/Teachers</span>
                        <a href="{{ route('admin.trainer.index') }}"><span class="info-box-number">{{ $total_trainers }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-graduation-cap"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Intakes</span>
                        <a href="{{ route('admin.intake.index') }}"><span class="info-box-number">{{ $total_intakes }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-money-bill"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Fee Received</span>
                        <a href="#"><span class="info-box-number">{{ $total_fee_received }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-money-bill"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Fees Due</span>
                        <a href="#"><span class="info-box-number">{{ $total_due_fees }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-ticket-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Opened Tickets</span>
                        <a href="{{ route('admin.ticket.index') }}"><span class="info-box-number">{{ $total_opened_tickets }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-ticket-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total In Progress Tickets</span>
                        <a href="{{ route('admin.ticket.index') }}?status=2"><span class="info-box-number">{{ $total_in_progress_tickets }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-8 connectedSortable">
                <!-- solid sales graph -->
                <div class="card bg-gradient" style="background-color: #a1ccd1;">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-th mr-1"></i>
                            Batch Data
                        </h3>

                        <div class="card-tools">
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-transparent">
                        <div class="row">
                            <div class="col-6 text-center">
                                <input type="text" class="knob" data-readonly="true" value="{{ $total_intakes }}" data-width="60" data-height="60" data-fgColor="#39CCCC" style="text-align:center">

                                <div class="text-white">Intakes</div>
                            </div>
                            <!-- ./col -->
                            <div class="col-6 text-center">
                                <input type="text" class="knob" data-readonly="true" value="{{ $total_students }}" data-width="60" data-height="60" data-fgColor="#39CCCC" style="text-align:center">

                                <div class="text-white">Students</div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->

                <!-- TABLE: LATEST ASSESMENT -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Latest Assessments</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped m-0">
                                @if(count($submissions) > 0)
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Due Date</th>
                                        <th>Submitted Date</th>
                                        <th>Teacher</th>
                                        <th>Grade</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $index => $value)
                                    <tr>
                                        <td><a href="{{ route('admin.student.show', $value->student_id) }}">{{ userName('Student', $value->student_id) }}</a></td>
                                        <td>{{ dateFormat($value->assignment->due_date) }}</td>
                                        <td>{{ dateFormat($value->created_at) }}</td>
                                        <td>{{ userName('Trainer', $value->assignment->trainer_id) }}</td>
                                        <td>@if ($value->assignment_grade_id == NULL) Not graded @else {{ $value->assignmentGrade->name }} @endif</td>
                                        <td>
                                            <a href="{{ route('admin.submission.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <h3>
                                    <center>No Data Found</center>
                                </h3>
                                @endif
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix card-footer-divide">
                        <a href="{{ route('admin.submission.index') }}" class="btn btn-sm btn-info float-left">View All Submissions</a>
                        <a href="{{ route('admin.assignment.index') }}" class="btn btn-sm btn-secondary float-right">View All Assessments</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->

                @if (checkRole('ticket', 'view') == true)
                <!-- TABLE: OPENED TICKETS -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Latest Opened Tickets</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped m-0">
                                @if(count($opened_tickets) > 0)
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Opened Date</th>
                                        <th>Subject</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($opened_tickets as $index => $value)
                                    <tr>
                                        <td><a href="{{ route('admin.student.show', $value->user_id) }}">{{ userName('Student', $value->user_id) }}</a></td>
                                        <td>{{ dateFormat($value->created_at) }}</td>
                                        <td>{{ $value->subject }}</td>
                                        <td>
                                            <a href="{{ route('admin.ticket.show', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <h3>
                                    <center>No Data Found</center>
                                </h3>
                                @endif
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix card-footer-divide">
                        <a href="{{ route('admin.ticket.index') }}?status=1" class="btn btn-sm btn-info float-left">View All Opened Tickets</a>
                        <a href="{{ route('admin.ticket.index') }}?status=2" class="btn btn-sm btn-secondary float-right">View All Inprogess Tickets</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                @endif
                <!-- /.card -->

                <!-- TABLE: STUDENT PAYMENT DUE -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Student Due Payments</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped m-0">
                                @if(count($payment_dues) > 0)
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment_dues as $index => $value)
                                    <tr>
                                        <td><a href="{{ route('admin.student.fee.index', $value->studentIntakeCourseFee->student_id) }}">{{ userName('Student', $value->studentIntakeCourseFee->student_id) }}</a></td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->amount }}</td>
                                        <td>{{ dateFormat($value->due_date) }}</td>
                                        <td>{{ studentPaymentStatus($value->status) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <h3>
                                    <center>No Data Found</center>
                                </h3>
                                @endif
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">

                    </div>
                    <!-- /.card-footer -->
                </div>
            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-4 connectedSortable">
                <!-- Custom tabs (Charts with tabs)-->

                <div class="card card-secondary">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{ $address_chart_title }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">{{ $total_students }}</span>
                                <span>Total Students</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                            </p>
                        </div>
                        <!-- /.d-flex -->

                        <div class="position-relative mb-4">
                            <canvas id="provinces-chart" height="200"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                        </div>
                    </div>
                </div>
                <!-- /.card -->

                <div @if (dashboardWidget('top_students_country_dashboard')=='on' ) style="display: block;" @else style="display:none;" @endif>
                    <div class="card card-secondary">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Top Students By Country</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg">{{ $total_students }}</span>
                                    <span>Total Students</span>
                                </p>
                                <p class="ml-auto d-flex flex-column text-right">
                                </p>
                            </div>
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <canvas id="sales-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->

                <!-- PIE CHART -->
                <div @if (dashboardWidget('students_country_dashboard')=='on' ) style="display: block;" @else style="display:none;" @endif>
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Students By Country</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->

                <!-- Pie Chart -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Students By Delivery Sites</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChartDeliverySite"></canvas>
                    </div>
                    <!-- /.card-body -->
                </div>

                <!-- Calendar -->
                <div class="card">
                    <div class="card-header border-0">

                        <h3 class="card-title">
                            <i class="far fa-calendar-alt"></i>
                            Calendar
                        </h3>
                        <!-- tools card -->
                        <div class="card-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a href="#" class="dropdown-item">Add new event</a>
                                    <a href="#" class="dropdown-item">Clear events</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">View calendar</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body pt-0">
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- TRAINERS LIST -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Faculty/Teachers</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <ul class="users-list clearfix">
                            @if (count($trainers) > 0)
                            @foreach ($trainers as $trainer)
                            <li>
                                <img src="{{ asset($trainer->image) }}" alt="User Image" style="width: 55px; height:55px; object-fit: contain; border: 2px solid #adb5bd; padding: 5px; max-width: initial;">
                                <a class="users-list-name" href="#">{{ userName('Trainer', $trainer->id) }}</a>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                        <!-- /.trainers-list -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.trainer.index') }}">View All Teacher</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!--/.card -->
            </section>
            <!-- right col -->

        </div>

        <!-- /.col -->
        <!-- Main row -->
        <div class="row">

            <!-- Left col -->
            <div class="col-md-4">

                <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="salesChart" height="180" style="height: 0px;"></canvas>
                </div>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.col -->
        <!-- /.row -->
    </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->

@else
{{-- ===== THEME 3: Enterprise Dashboard ===== --}}
<style>
    .dashboard-page-title {
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
        color: #111827;
    }

    .dashboard-subtitle {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .dark-mode .dashboard-page-title {
        color: #f8fafc;
    }

    .dark-mode .dashboard-subtitle {
        color: #cbd5e1;
    }

    @media (max-width: 575.98px) {
        .dashboard-page-title {
            font-size: 21px;
        }
    }
</style>
<div class="enterprise-dashboard">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-7">
                    <h1 class="dashboard-page-title mb-0">Dashboard</h1>
                    <p class="dashboard-subtitle">Operational overview for courses, students, fees, tickets, and delivery performance.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.course.index') }}">
                        <span class="metric-icon metric-blue"><i class="fas fa-clipboard"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Courses</span>
                            <span class="metric-value">{{ number_format($total_courses) }}</span>
                            <span class="metric-note">Active and inactive course catalogue</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.student.index') }}">
                        <span class="metric-icon metric-cyan"><i class="fas fa-user-graduate"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Students</span>
                            <span class="metric-value">{{ number_format($total_students) }}</span>
                            <span class="metric-note">Currently enrolled learners</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.trainer.index') }}">
                        <span class="metric-icon metric-slate"><i class="fas fa-chalkboard-teacher"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Faculty / Staffs</span>
                            <span class="metric-value">{{ number_format($total_trainers) }}</span>
                            <span class="metric-note">Teaching staff in the system</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.intake.index') }}">
                        <span class="metric-icon metric-amber"><i class="fas fa-calendar-check"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Intakes</span>
                            <span class="metric-value">{{ number_format($total_intakes) }}</span>
                            <span class="metric-note">Scheduled intake cohorts</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="#">
                        <span class="metric-icon metric-green"><i class="fas fa-dollar-sign"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Total Fee Received</span>
                            <span class="metric-value">{{ number_format((float) $total_fee_received, 2) }}</span>
                            <span class="metric-note">Collected student payments</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="#">
                        <span class="metric-icon metric-red"><i class="fas fa-file-invoice-dollar"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Total Fees Due</span>
                            <span class="metric-value">{{ number_format((float) $total_due_fees, 2) }}</span>
                            <span class="metric-note">Outstanding fee balance</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.ticket.index') }}?status=1">
                        <span class="metric-icon metric-green"><i class="fas fa-ticket-alt"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">Opened Tickets</span>
                            <span class="metric-value">{{ number_format($total_opened_tickets) }}</span>
                            <span class="metric-note">Awaiting first resolution action</span>
                        </span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <a class="metric-card" href="{{ route('admin.ticket.index') }}?status=2">
                        <span class="metric-icon metric-blue"><i class="fas fa-tasks"></i></span>
                        <span class="metric-content">
                            <span class="metric-label">In Progress Tickets</span>
                            <span class="metric-value">{{ number_format($total_in_progress_tickets) }}</span>
                            <span class="metric-note">Support work currently underway</span>
                        </span>
                    </a>
                </div>
            </div>

            <div class="row">
                <section class="col-xl-8 connectedSortable">
                    <div class="card dashboard-panel chart-feature">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-line"></i> Batch Data</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-frame">
                                <canvas id="line-chart"></canvas>
                            </div>
                        </div>
                        <div class="chart-summary">
                            <div class="chart-summary-item">
                                <span class="chart-summary-label">Intakes</span>
                                <span class="chart-summary-value">{{ number_format($total_intakes) }}</span>
                            </div>
                            <div class="chart-summary-item">
                                <span class="chart-summary-label">Students</span>
                                <span class="chart-summary-value">{{ number_format($total_students) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Latest Assessments</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if(count($submissions) > 0)
                            <div class="table-responsive">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Due Date</th>
                                            <th>Submitted Date</th>
                                            <th>Teacher</th>
                                            <th>Grade</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $value)
                                        <tr>
                                            <td><a href="{{ route('admin.student.show', $value->student_id) }}">{{ userName('Student', $value->student_id) }}</a></td>
                                            <td>{{ dateFormat(optional($value->assignment)->due_date) }}</td>
                                            <td>{{ dateFormat($value->created_at) }}</td>
                                            <td>{{ userName('Trainer', optional($value->assignment)->trainer_id) }}</td>
                                            <td>
                                                @if ($value->assignment_grade_id == NULL)
                                                <span class="badge badge-light">Not graded</span>
                                                @else
                                                <span class="badge badge-success">{{ $value->assignmentGrade->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.submission.edit', $value->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="dashboard-empty">
                                <i class="fas fa-inbox"></i>
                                No assessment submissions found.
                            </div>
                            @endif
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.submission.index') }}" class="panel-action float-left"><i class="fas fa-list"></i> View Submissions</a>
                            <a href="{{ route('admin.assignment.index') }}" class="panel-action float-right"><i class="fas fa-clipboard-list"></i> View Assessments</a>
                        </div>
                    </div>

                    @if (checkRole('ticket', 'view') == true)
                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-life-ring"></i> Latest Opened Tickets</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if(count($opened_tickets) > 0)
                            <div class="table-responsive">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Opened Date</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($opened_tickets as $value)
                                        <tr>
                                            <td><a href="{{ route('admin.student.show', $value->user_id) }}">{{ userName('Student', $value->user_id) }}</a></td>
                                            <td>{{ dateFormat($value->created_at) }}</td>
                                            <td>{{ $value->subject }}</td>
                                            <td>
                                                <a href="{{ route('admin.ticket.show', $value->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="dashboard-empty">
                                <i class="fas fa-check-circle"></i>
                                No opened tickets found.
                            </div>
                            @endif
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ route('admin.ticket.index') }}?status=1" class="panel-action float-left"><i class="fas fa-ticket-alt"></i> Opened Tickets</a>
                            <a href="{{ route('admin.ticket.index') }}?status=2" class="panel-action float-right"><i class="fas fa-spinner"></i> In Progress Tickets</a>
                        </div>
                    </div>
                    @endif

                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Student Due Payments</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if(count($payment_dues) > 0)
                            <div class="table-responsive">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payment_dues as $value)
                                        <tr>
                                            <td><a href="{{ route('admin.student.fee.index', $value->studentIntakeCourseFee->student_id) }}">{{ userName('Student', $value->studentIntakeCourseFee->student_id) }}</a></td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ number_format((float) $value->amount, 2) }}</td>
                                            <td>{{ dateFormat($value->due_date) }}</td>
                                            <td>{!! studentPaymentStatus($value->status) !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="dashboard-empty">
                                <i class="fas fa-calendar-check"></i>
                                No due payments in the next seven days.
                            </div>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="col-xl-4 connectedSortable">
                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> {{ $address_chart_title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mini-chart-stat">
                                <div>
                                    <strong>{{ number_format($total_students) }}</strong>
                                    <span>Total Students</span>
                                </div>
                            </div>
                            <div class="chart-canvas-box">
                                <canvas id="provinces-chart" height="220"></canvas>
                            </div>
                        </div>
                    </div>

                    <div @if (dashboardWidget('top_students_country_dashboard') == 'on') style="display: block;" @else style="display:none;" @endif>
                        <div class="card dashboard-panel">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-globe-asia"></i> Top Students By Country</h3>
                            </div>
                            <div class="card-body">
                                <div class="mini-chart-stat">
                                    <div>
                                        <strong>{{ number_format($total_students) }}</strong>
                                        <span>Total Students</span>
                                    </div>
                                </div>
                                <div class="chart-canvas-box">
                                    <canvas id="sales-chart" height="220"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div @if (dashboardWidget('students_country_dashboard') == 'on') style="display: block;" @else style="display:none;" @endif>
                        <div class="card dashboard-panel">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Students By Country</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($hasCountryChartData)
                                <div class="chart-canvas-box chart-canvas-doughnut">
                                    <canvas id="pieChart" height="240"></canvas>
                                </div>
                                @else
                                <div class="dashboard-empty">
                                    <i class="fas fa-chart-pie"></i>
                                    No student country data available.
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-building"></i> Students By Delivery Sites</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-canvas-box">
                                <canvas id="pieChartDeliverySite"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="far fa-calendar-alt"></i> Calendar</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="panel-tool-btn dropdown-toggle" data-toggle="dropdown" data-offset="-52" title="Calendar menu">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a href="#" class="dropdown-item">Add new event</a>
                                        <a href="#" class="dropdown-item">Clear events</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">View calendar</a>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div id="calendar" class="calendar-shell"></div>
                        </div>
                    </div>

                    <div class="card dashboard-panel">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users"></i> Faculty / Staffs</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if (count($trainers) > 0)
                            <ul class="teacher-list">
                                @foreach ($trainers as $trainer)
                                <li class="teacher-item">
                                    <img class="teacher-avatar" src="{{ asset($trainer->image) }}" alt="{{ userName('Trainer', $trainer->id) }}">
                                    <a class="teacher-name" href="{{ route('admin.trainer.index') }}">{{ userName('Trainer', $trainer->id) }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="dashboard-empty">
                                <i class="fas fa-user-slash"></i>
                                No faculty records found.
                            </div>
                            @endif
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('admin.trainer.index') }}" class="panel-action"><i class="fas fa-arrow-right"></i> View All Faculties/Staffs</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
{{-- ===== END THEME 3 ===== --}}
@endif
@endsection

@section('scripts')
@if ($userTheme != 'theme3')
<script>
    $(function() {
        'use strict'

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }

        var mode = 'index'
        var intersect = true

        var $salesChart = $('#sales-chart')
        var salesChart = new Chart($salesChart, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($top_country); ?>,
                datasets: [{
                        backgroundColor: ['#C98474', '#7FBCD2', '#A7D2CB', '#A78295', '#C4C1A4'],
                        borderColor: '#007bff',
                        data: <?php echo json_encode($top_count); ?>,
                    },
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function(value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                },
                onClick: function(e) {
                    debugger;
                    var link = "{{ route('admin.student.index') }}?citizenship_country=";
                    var activePointLabel = this.getElementsAtEvent(e)[0]._model.label;
                    var fullLink = link.concat(activePointLabel);
                    location.href = fullLink;
                }
            }
        })

        var $agentChart = $('#agent-chart')
        var agentChart = new Chart($agentChart, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($top_agent); ?>,
                datasets: [{
                        backgroundColor: ['#A8A196', '#9BABB8', '#7C96AB', '#413543', '#DBC4F0'],
                        borderColor: '#007bff',
                        data: <?php echo json_encode($top_agent_count); ?>,
                    },
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function(value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })

        var $provinceChart = $('#provinces-chart')
        var provinceChart = new Chart($provinceChart, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($top_provinces); ?>,
                datasets: [{
                        backgroundColor: ['#C98474', '#7FBCD2', '#A7D2CB', '#A78295', '#C4C1A4'],
                        borderColor: '#007bff',
                        data: <?php echo json_encode($top_provinces_count); ?>,
                    },
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function(value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })

        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData = {
            labels: <?php echo json_encode($countries); ?>,
            datasets: [{
                data: <?php echo json_encode($student_counts); ?>,
                backgroundColor: <?php echo json_encode($colors); ?>,
            }]
        }
        var pieOptions = {
            legend: {
                display: false
            }
        }
        var pieChart = new Chart(pieChartCanvas, {
            type: 'doughnut',
            data: pieData,
            options: pieOptions
        })

        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')

        var salesGraphChartData = {
            labels: <?php echo json_encode($chart_intakes); ?>,
            datasets: [{
                label: 'Students',
                fill: false,
                borderWidth: 2,
                lineTension: 0,
                spanGaps: true,
                borderColor: '#11655b',
                pointRadius: 3,
                pointHoverRadius: 7,
                pointColor: '#ffeebb',
                pointBackgroundColor: '#9ac5f4',
                data: <?php echo json_encode($chart_students); ?>,
            }]
        }

        var salesGraphChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: '#0a4d68'
                    },
                    gridLines: {
                        display: false,
                        color: '#088395',
                        drawBorder: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 5,
                        fontColor: '#0a4d68'
                    },
                    gridLines: {
                        display: true,
                        color: '#0a4d68',
                        drawBorder: false
                    }
                }]
            }
        }

        var salesGraphChart = new Chart(salesGraphChartCanvas, {
            type: 'line',
            data: salesGraphChartData,
            options: salesGraphChartOptions
        })
    })

    var ctx = document.getElementById('pieChartDeliverySite').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($data['labels']); ?>,
            datasets: [{
                data: <?php echo json_encode($data['data']); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        },
    })
</script>

@else
{{-- ===== THEME 3 SCRIPTS ===== --}}
<script>
    $(function() {
        'use strict';

        var ticksStyle = {
            fontColor: '#64748b',
            fontStyle: 'bold'
        };
        var mode = 'index';
        var intersect = true;

        function hasCanvas(selector) {
            return $(selector).length && $(selector).is(':visible');
        }

        function compactTick(value) {
            if (value >= 1000) {
                value = value / 1000 + 'k';
            }
            return value;
        }

        if ($('#calendar').length && $.fn.datetimepicker) {
            $('#calendar').datetimepicker({
                format: 'L',
                inline: true
            });
        }

        if (hasCanvas('#sales-chart')) {
            new Chart($('#sales-chart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($top_country); ?>,
                    datasets: [{
                        backgroundColor: ['#2563eb', '#0891b2', '#059669', '#d97706', '#475569'],
                        borderColor: '#2563eb',
                        data: <?php echo json_encode($top_count); ?>,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: { mode: mode, intersect: intersect },
                    hover: { mode: mode, intersect: intersect },
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            gridLines: { display: true, color: 'rgba(148, 163, 184, .22)', zeroLineColor: 'rgba(148, 163, 184, .38)' },
                            ticks: $.extend({ beginAtZero: true, callback: compactTick }, ticksStyle)
                        }],
                        xAxes: [{ display: true, gridLines: { display: false }, ticks: ticksStyle }]
                    },
                    onClick: function(e) {
                        var points = this.getElementsAtEvent(e);
                        if (!points.length) return;
                        location.href = "{{ route('admin.student.index') }}?citizenship_country=" + points[0]._model.label;
                    }
                }
            });
        }

        if (hasCanvas('#provinces-chart')) {
            new Chart($('#provinces-chart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($top_provinces); ?>,
                    datasets: [{
                        backgroundColor: ['#2563eb', '#0891b2', '#059669', '#d97706', '#475569', '#7c3aed', '#dc2626', '#0f766e'],
                        borderColor: '#2563eb',
                        data: <?php echo json_encode($top_provinces_count); ?>,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: { mode: mode, intersect: intersect },
                    hover: { mode: mode, intersect: intersect },
                    legend: { display: false },
                    scales: {
                        yAxes: [{
                            gridLines: { display: true, color: 'rgba(148, 163, 184, .22)', zeroLineColor: 'rgba(148, 163, 184, .38)' },
                            ticks: $.extend({ beginAtZero: true, callback: compactTick }, ticksStyle)
                        }],
                        xAxes: [{ display: true, gridLines: { display: false }, ticks: ticksStyle }]
                    }
                }
            });
        }

        if ($('#pieChart').length && <?php echo json_encode($hasCountryChartData); ?>) {
            new Chart($('#pieChart').get(0).getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($countries); ?>,
                    datasets: [{
                        data: <?php echo json_encode($student_counts); ?>,
                        backgroundColor: <?php echo json_encode($colors); ?>,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: { display: false },
                    cutoutPercentage: 64
                }
            });
        }

        if ($('#line-chart').length) {
            new Chart($('#line-chart').get(0).getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($chart_intakes); ?>,
                    datasets: [{
                        label: 'Students',
                        fill: true,
                        backgroundColor: 'rgba(37, 99, 235, .08)',
                        borderWidth: 3,
                        lineTension: .25,
                        spanGaps: true,
                        borderColor: '#2563eb',
                        pointRadius: 3,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#0891b2',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        data: <?php echo json_encode($chart_students); ?>,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: { fontColor: '#64748b', fontStyle: 'bold' },
                            gridLines: { display: false, drawBorder: false }
                        }],
                        yAxes: [{
                            ticks: { beginAtZero: true, precision: 0, fontColor: '#64748b', fontStyle: 'bold' },
                            gridLines: { display: true, color: 'rgba(148, 163, 184, .22)', drawBorder: false }
                        }]
                    }
                }
            });
        }

        if ($('#pieChartDeliverySite').length) {
            new Chart(document.getElementById('pieChartDeliverySite').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($data['labels'] ?? []); ?>,
                    datasets: [{
                        data: <?php echo json_encode($data['data'] ?? []); ?>,
                        backgroundColor: [
                            'rgba(37, 99, 235, 0.78)',
                            'rgba(8, 145, 178, 0.78)',
                            'rgba(5, 150, 105, 0.78)',
                            'rgba(217, 119, 6, 0.78)',
                            'rgba(71, 85, 105, 0.78)',
                            'rgba(124, 58, 237, 0.78)'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: { display: false },
                    cutoutPercentage: 62
                }
            });
        }
    });
</script>
@endif
@endsection
