@extends('user::layouts.master')
@section('title', 'Admin | Add Email Setting')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Email Setting</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.email.index') }}">Emails</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addemail" action="{{ route('admin.email.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Email Setting</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4"> <span class="required">*</span>
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="host">Host</label> <span class="required">*</span>
                                    <input type="text" name="host" class="form-control" id="host" placeholder="Enter Host">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="port">Port</label> <span class="required">*</span>
                                    <input type="text" name="port" class="form-control" id="port" placeholder="Enter Port">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="username">Username</label> <span class="required">*</span>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="password">Password</label> <span class="required">*</span>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="encryption">Encryption</label> <span class="required">*</span>
                                    <input type="text" name="encryption" class="form-control" id="encryption" placeholder="Enter Encryption (E.g. ssl, tls etc)">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="from">Email From</label> <span class="required">*</span>
                                    <input type="email" name="from_address" class="form-control" id="from_address" placeholder="Enter Email From (admin@eloom.com)">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="name">Name From</label> <span class="required">*</span>
                                    <input type="text" name="from_name" class="form-control" id="from_name" placeholder="Enter Name From (Eloom Pty. Ltd.)">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="reply_to">Reply To email</label> <span class="required">*</span>
                                    <input type="email" name="reply_to" class="form-control" id="reply_to" placeholder="Enter Reply To email">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="cc">CC</label>
                                    <textarea name="cc" class="form-control" id="cc" placeholder="Enter CC"></textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="bcc">BCC</label>
                                    <textarea name="bcc" class="form-control" id="bcc" placeholder="Enter BCC"></textarea>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
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
        $('#addemail').validate({
            rules: {
                name: {
                    required: true,
                },
                host: {
                    required: true,
                },
                port: {
                    required: true,
                },
                username: {
                    required: true,
                },
                password: {
                    required: true,
                },
                encryption: {
                    required: true,
                },
                from_address: {
                    required: true,
                },
                from_name: {
                    required: true,
                },
                reply_to: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
                host: "Please enter host",
                port: "Please enter port",
                username: "Please enter username",
                password: "Please enter password",
                encryption: "Please enter encryption",
                from_address: "Please enter from address",
                from_name: "Please enter from name",
                reply_to: "Please enter reply to",
                status: "Please select one status",

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