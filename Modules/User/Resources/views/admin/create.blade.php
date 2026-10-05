@extends('user::layouts.master')
@section('title', 'Admin | Add User')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Users</a></li>
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
        <form id="adduser" action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add User</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="first_name">First Name</label> <span class="required">*</span>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="family_name">Family Name</label> <span class="required">*</span>
                                    <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                </div>
                                <div class="form-group col-md-4">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="phone">Phone</label> <span class="required">*</span>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label> <span class="required">*</span>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="password">Password</label> <span class="required">*</span>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="image">Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 100px; border: #ebebeb 1px solid;">
                                </div>
                                <div class="form-group col-md-4">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country">Country</label> <span class="required">*</span>
                                    <select class="form-control" name="country_id">
                                        <option value="">-- Select Company Country --</option>
                                        @foreach($countries as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="user_type">Role</label> <span class="required">*</span>
                                    <select class="form-control" name="user_type">
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $key => $value)
                                        <option value="{{ $value->user_type }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="1" selected>Active</option>
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
        $('#adduser').validate({
            rules: {
                first_name: {
                    required: true,
                },
                family_name: {
                    required: true,
                },
                phone: {
                    required: true,
                    minlength: 7
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
                country_id: {
                    required: true
                },
                user_type: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                first_name: "Please enter first_name",
                family_name: "Please enter family name",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone numver must be at least 7 characters long"
                },
                email: "Please enter email",
                password: "Please enter password",
                country_id: "Please select one country",
                user_type: "Please select one role",
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

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection