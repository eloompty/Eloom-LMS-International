@extends('user::layouts.master')
@section('title', 'Admin | Edit Teacher Professional Development')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Teacher Professional Development</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.profession.index', $profession->trainer_id) }}">Professional Developments</a></li>
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
        <form id="edittrainerprofession" action="{{ route('admin.trainer.profession.update', $profession->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ $profession->trainer->salutation }} {{ $profession->trainer->first_name }} {{ $profession->trainer->family_name }}'s Professional Development</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="title">Title</label> <span class="required">*</span>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" value="{{ $profession->title }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="duration">Duration</label> <span class="required">*</span>
                                    <input type="number" name="duration" class="form-control" id="duration" placeholder="Enter Duration" value="{{ $profession->duration}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="start_date">Start Date</label> <span class="required">*</span>
                                    <input type="date" name="start_date" class="form-control" id="start_date" placeholder="Enter Start Date" value="{{ $profession->start_date}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="end_date">End Date</label> <span class="required">*</span>
                                    <input type="date" name="end_date" class="form-control" id="end_date" placeholder="Enter End Date" value="{{ $profession->end_date}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($profession->status == '1')selected @endif value="1">Active</option>
                                        <option @if($profession->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
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

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("Form successful submitted!");
        //     }
        // });
        $('#edittrainerprofession').validate({
            rules: {
                title: {
                    required: true,
                },
                duration: {
                    required: true,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                title: "Please enter title",
                duration: "Please enter duration",
                start_date: "Please enter start date",
                end_date: "Please enter end date",
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