@extends('agent::agent.layouts.master')
@section('title', 'Agent Profile')

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
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Home</a></li>
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
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset(Auth::guard('agent')->user()->image) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::guard('agent')->user()->name }}</h3>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Company</b> <a class="float-right">{{ Auth::guard('agent')->user()->company_name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Company Registration</b> <a class="float-right">{{ Auth::guard('agent')->user()->company_registration }}</a>
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
                        <p class="text-muted">{{ Auth::guard('agent')->user()->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ Auth::guard('agent')->user()->mobile }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Office Phone</strong>
                        <p class="text-muted">{{ Auth::guard('agent')->user()->office_mobile }}</p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                        <p class="text-muted">{{ Auth::guard('agent')->user()->country->name }}, {{ Auth::guard('agent')->user()->city }}, {{ Auth::guard('agent')->user()->address }}</p>

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
                                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::guard('agent')->user()->name }}" placeholder="Name" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company_name" class="col-sm-2 col-form-label">Company Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{ Auth::guard('agent')->user()->company_name }}" placeholder="Company Name" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company_registration" class="col-sm-2 col-form-label">Company Registration</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="company_registration" name="company_registration" value="{{ Auth::guard('agent')->user()->company_registration }}" placeholder="Company Registration" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::guard('agent')->user()->email }}" placeholder="Email" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-sm-2 col-form-label">Mobile</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="mobile" name="mobile" value="{{ Auth::guard('agent')->user()->mobile }}" placeholder="Mobile" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="office_phone" class="col-sm-2 col-form-label">Office Phone</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="office_phone" name="office_phone" value="{{ Auth::guard('agent')->user()->office_phone }}" placeholder="Office Phone" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="country" class="col-sm-2 col-form-label">Country</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="country" name="country" value="{{ Auth::guard('agent')->user()->country->name }}" placeholder="Country" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="city" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="city" name="city" value="{{ Auth::guard('agent')->user()->city }}" placeholder="City" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" name="address" value="{{ Auth::guard('agent')->user()->address }}" placeholder="Address" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="url" class="col-sm-2 col-form-label">URL</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="url" name="url" value="{{ Auth::guard('agent')->user()->url }}" placeholder="URL" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="rate" class="col-sm-2 col-form-label">Rate</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="rate" name="rate" value="{{ Auth::guard('agent')->user()->rate }}" placeholder="Rate" disabled>
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