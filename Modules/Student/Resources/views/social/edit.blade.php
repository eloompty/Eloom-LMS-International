@extends('user::layouts.master')
@section('title', 'Admin | Edit Student Social')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Student Social</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.social.index', $studentSocial->user_id) }}">Socials</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="editstudentsocial" action="{{ route('admin.student.social.update', $studentSocial->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ userName('Student', $studentSocial->user_id) }}'s Social</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="social_category_id">Social Category</label> <span class="required">*</span>
                                <select id="social_category_id" name="social_category_id" class="form-control">
                                    <option value="" selected disabled>-- Select Social Category --</option>
                                    @foreach($socials as $key => $value)
                                    <option value="{{ $key }}" @if ($key==$studentSocial->social_category_id) selected @endif> {{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="social_link">Social Link</label> <span class="required">*</span>
                                <input type="text" name="social_link" class="form-control" id="social_link" placeholder="Enter Social Link" value="{{ $studentSocial->social_link }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($studentSocial->status == '1')selected @endif value="1">Active</option>
                                    <option @if($studentSocial->status == '0')selected @endif value="0">Inactive</option>
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
        $('#editstudentsocial').validate({
            rules: {
                social_category_id: {
                    required: true,
                },
                social_link: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                social_category_id: "Please choose one social category",
                social_category_id: "Please enter social link",
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