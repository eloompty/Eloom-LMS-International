@extends('user::layouts.master')
@section('title', 'Admin | Zoho Settings')

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
                <h1>Zoho Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Zoho Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="updatesetting" action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Zoho Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="zoho_client_id">Zoho Client ID</label>
                                    <textarea name="zoho_client_id" class="form-control" id="zoho_client_id" placeholder="Enter Zoho Client ID">{{ getSettingValue('zoho_client_id') }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="zoho_client_secret">Zoho Client Secret</label>
                                    <textarea name="zoho_client_secret" class="form-control" id="zoho_client_secret" placeholder="Enter Zoho Client Secret">{{ getSettingValue('zoho_client_secret') }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="zoho_redirect_uri">Zoho Redirect URI</label>
                                    <textarea name="zoho_redirect_uri" class="form-control" id="zoho_redirect_uri" placeholder="Enter Zoho Redirect URI">{{ getSettingValue('zoho_redirect_uri') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </form>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection