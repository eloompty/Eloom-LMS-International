@extends('agentbranchuser::user.layouts.master')
@section('title', 'Agent Branch User | Dashboard')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch-user.dashboard') }}">Home</a></li>
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
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Enrolled Students</span>
                        <a href="{{ route('branch-user.student.enrolled.index') }}">
                            <span class="info-box-number">
                                {{ $total_enrolled_student }}
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
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Offer Students</span>
                        <a href="{{ route('branch-user.student.index') }}"><span class="info-box-number">{{ $total_student }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <div class="col-md-6">
                <!-- Calendar -->

                <!-- /.col -->
            </div>
            <!-- Left col -->
            <div class="col-md-6">

            </div>
            <!-- /.col -->
        </div>
        <!--/. container-fluid -->
</section>
<!-- /.content -->
@endsection