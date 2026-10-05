@extends('agentbranchuser::user.layouts.master')
@section('title', 'Agent Branch User | Profile')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch-user.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset(Auth::guard('agent_branch_user')->user()->image) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::guard('agent_branch_user')->user()->name }}</h3>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Agent</b> <a class="float-right">{{ Auth::guard('agent_branch_user')->user()->branch->agent->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Branch</b> <a class="float-right">{{ Auth::guard('agent_branch_user')->user()->branch->name }}</a>
                            </li>
                        </ul>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ Auth::guard('agent_branch_user')->user()->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ Auth::guard('agent_branch_user')->user()->phone }}</p>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::guard('agent_branch_user')->user()->name }}" placeholder="Name" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="agent" class="col-sm-2 col-form-label">Agent</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="agent" name="agent" value="{{ Auth::guard('agent_branch_user')->user()->branch->agent->name }}" placeholder="Agent" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branch" class="col-sm-2 col-form-label">Branch</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="branch" name="branch" value="{{ Auth::guard('agent_branch_user')->user()->branch->name }}" placeholder="Branch" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::guard('agent_branch_user')->user()->email }}" placeholder="Email" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="phone" name="phone" value="{{ Auth::guard('agent_branch_user')->user()->phone }}" placeholder="Phone" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection