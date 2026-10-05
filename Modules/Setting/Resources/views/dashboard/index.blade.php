@extends('user::layouts.master')
@section('title', 'Admin | Dashboard Settings')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Dashboard Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- form start -->
    <form id="updatesetting" action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Dashboard Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="top_students_country_dashboard">Top Students By Country in Dashboard</label>
                                        <select name="top_students_country_dashboard" class="form-control" id="top_students_country_dashboard">
                                            <option @if(dashboardWidget('top_students_country_dashboard')=='on' )selected @endif value="on">On</option>
                                            <option @if(dashboardWidget('top_students_country_dashboard')=='off' )selected @endif value="off">Off</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="top_students_agent_dashboard">Top Students By Agent in Dashboard</label>
                                        <select name="top_students_agent_dashboard" class="form-control" id="top_students_agent_dashboard">
                                            <option @if(dashboardWidget('top_students_agent_dashboard')=='on' )selected @endif value="on">On</option>
                                            <option @if(dashboardWidget('top_students_agent_dashboard')=='off' )selected @endif value="off">Off</option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="students_country_dashboard">Students By Country in Dashboard</label>
                                        <select name="students_country_dashboard" class="form-control" id="students_country_dashboard">
                                            <option @if(dashboardWidget('students_country_dashboard')=='on' )selected @endif value="on">On</option>
                                            <option @if(dashboardWidget('students_country_dashboard')=='off' )selected @endif value="off">Off</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div><!-- /.container-fluid -->
    </form>
</section>
<!-- /.content -->
@endsection