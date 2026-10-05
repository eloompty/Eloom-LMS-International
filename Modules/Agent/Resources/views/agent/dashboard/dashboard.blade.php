@extends('agent::agent.layouts.master')
@section('title', 'Agent | Dashboard')

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
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Home</a></li>
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
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-code-branch"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Branches</span>
                        <a href="{{ route('agent.branch.index') }}">
                            <span class="info-box-number">
                                {{ $total_branch }}
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
                        <span class="info-box-text">Users</span>
                        <a href="{{ route('agent.user.index') }}"><span class="info-box-number">{{ $total_user }}</span></a>
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
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-friends"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Offer Students</span>
                        <a href="{{ route('agent.student.index') }}"><span class="info-box-number">{{ $total_student }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-friends"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Enrolled Students</span>
                        <a href="{{ route('agent.student.enrolled.index') }}"><span class="info-box-number">{{ $total_enrolled_student }}</span></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

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