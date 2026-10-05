@extends('user::layouts.master')
@section('title', 'Admin | Add Branch User')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Branch User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.index') }}">Agents</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.branch.index', $branch->agent_id) }}">Branches</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.branch.user.index', $branch->id) }}">Users</a></li>
                    <li class="breadcrumb-item active">Add Branch User</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="adduser" action="{{ route('admin.agent.branch.user.store', $branch->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ $branch->name }}'s User</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label> <span class="required">*</span>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label> <span class="required">*</span>
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label> <span class="required">*</span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label> <span class="required">*</span>
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
        $('#adduser').validate({
            rules: {
                name: {
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
                phone: {
                    required: true,
                    digits: true,
                    minlength: 7
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
                country_id: "Please choose one country",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                city: "Please enter city",
                address: "Please enter address",
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