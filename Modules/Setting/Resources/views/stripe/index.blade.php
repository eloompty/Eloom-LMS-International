@extends('user::layouts.master')
@section('title', 'Admin | Stripe Settings')

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
                <h1>Stripe Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Stripe Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="updatesetting" action="{{ route('admin.setting.stripe.update') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Stripe Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="stripe_key">Stripe Key</label>
                                    <textarea name="stripe_key" class="form-control" id="stripe_key" placeholder="Enter Stripe Key">{{ getSettingValue('stripe_key') }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="stripe_secret">Stripe Secret</label>
                                    <textarea name="stripe_secret" class="form-control" id="stripe_secret" placeholder="Enter Stripe Secret">{{ getSettingValue('stripe_secret') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
    </div>
    <!-- /.row -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection