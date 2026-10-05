@extends('user::layouts.master')
@section('title', 'Admin | Add Agent')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Agent</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.index') }}">Agents</a></li>
                    <li class="breadcrumb-item active">Add Agent</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addagent" action="{{ route('admin.agent.store') }}" method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company Name</label> <span class="required">*</span>
                                <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Enter Company Name">
                            </div>
                            <div class="form-group">
                                <label for="company_registration">Company Registration</label> <span class="required">*</span>
                                <input type="text" name="company_registration" class="form-control" id="company_registration" placeholder="Enter Company Registration">
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile</label> <span class="required">*</span>
                                <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile">
                            </div>
                            <div class="form-group">
                                <label for="office_phone">Office Phone</label> <span class="required">*</span>
                                <input type="text" name="office_phone" class="form-control" id="office_phone" placeholder="Enter Office Phone">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label> <span class="required">*</span>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label> <span class="required">*</span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Country</label> <span class="required">*</span>
                                <select class="form-control" name="country_id">
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label> <span class="required">*</span>
                                <input type="text" name="city" class="form-control" id="city" placeholder="Enter City">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label> <span class="required">*</span>
                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address">
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" name="url" class="form-control" id="url" placeholder="Enter URL">
                            </div>
                            <div class="form-group">
                                <label for="rate">Rate</label> <span class="required">*</span>
                                <input type="number" name="rate" class="form-control" id="rate" placeholder="Enter Rate">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
        $('#addagent').validate({
            rules: {
                name: {
                    required: true,
                },
                company_name: {
                    required: true,
                },
                company_registration: {
                    required: true,
                },
                office_phone: {
                    required: true,
                    digits: true,
                    minlength: 7
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 7
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
                country_id: {
                    required: true,
                },
                city: {
                    required: true,
                },
                address: {
                    required: true,
                },
                rate: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
                company_name: "Please enter company name",
                company_registration: "Please enter company registration",
                office_phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                mobile: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                email: "Please enter email",
                password: "Please enter password",
                country_id: "Please choose one country",
                city: "Please enter city",
                address: "Please enter address",
                rate: "Please enter rate",
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
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection