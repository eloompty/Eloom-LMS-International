@extends('user::layouts.master')
@section('title', 'Admin | Add Email Templates')

@section('content')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Email Templates</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.email.template.index') }}">Templates</a></li>
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
        <form id="addtemplate" action="{{ route('admin.email.template.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Email Template</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6"> <span class="required">*</span>
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="subject">Subject</label> <span class="required">*</span>
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Enter Subject">
                                </div>
                                <div class="form-group  col-sm-12">
                                    <label for="content">Content</label> <span class="required">*</span>
                                    <textarea name="content" class="form-control" id="content" placeholder="Enter Content"></textarea>
                                    <script>
                                        CKEDITOR.replace('content');
                                    </script>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="regards_name">Regards Name</label> <span class="required">*</span>
                                    <input type="text" name="regards_name" class="form-control" id="regards_name" placeholder="Enter Regards Name">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="regards_position">Regards Position</label> <span class="required">*</span>
                                    <input type="text" name="regards_position" class="form-control" id="regards_position" placeholder="Enter Regards Postion">
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
        $('#addtemplate').validate({
            rules: {
                name: {
                    required: true,
                },
                subject: {
                    required: true,
                },
                content: {
                    required: true,
                },
                regards_name: {
                    required: true,
                },
                regards_position: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
                subject: "Please enter subject",
                content: "Please enter content",
                regards_name: "Please enter regards name",
                regards_position: "Please enter regards position",
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