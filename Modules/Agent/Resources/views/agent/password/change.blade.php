@extends('agent::agent.layouts.master')
@section('title', 'Agent | Change Password')

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
                <h1>Change Password</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="changepassword" action="{{ route('agent.fill.password') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Change Password</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="current_password">Current Password</label> <span class="required">*</span>
                                <input type="password" name="current_password" class="form-control" id="current_password" placeholder="Enter Current Password">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label> <span class="required">*</span>
                                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter New Password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label> <span class="required">*</span>
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password">
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

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#changepassword').validate({
            rules: {
                current_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                },
                confirm_password: {
                    required: true,
                },
            },
            messages: {
                current_password: "Please enter current password",
                new_password: "Please enter new password",
                confirm_password: "Please enter confirm password",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection