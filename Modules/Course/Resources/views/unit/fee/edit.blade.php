@extends('user::layouts.master')
@section('title', 'Admin | Edit Course Unit Fee')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 Course Unit>{{ $fee->unit->name }}'s Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($fee->unit->course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($fee->unit->course->registered == 1) href="{{ route('admin.course.unit.index', $fee->unit->course_id) }}" @else href="{{ route('admin.unregistered.unit.index', $fee->unit->course_id) }}" @endif>Units</a></li>
                    <li class="breadcrumb-item"><a @if ($fee->unit->course->registered == 1) href="{{ route('admin.course.unit.fee.index', $fee->unit->id) }}" @else href="{{ route('admin.unregistered.unit.fee.index', $fee->unit->id) }}" @endif>Fees</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit <small>{{ $fee->unit->name }}'s Fee</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="updatefee" action="{{ route('admin.course.unit.fee.update', $fee->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $fee->name }}">
                            </div>
                            <div class="form-group">
                                <label for="fee">Fee</label> <span class="required">*</span>
                                <input type="text" name="fee" class="form-control" id="fee" placeholder="Enter Fee" value="{{ $fee->fee }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($fee->status == '1')selected @endif value="1">Active</option>
                                    <option @if($fee->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
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
        $('#updatefee').validate({
            rules: {
                name: {
                    required: true,
                },
                fee: {
                    required: true,
                    digits: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter category name",
                fee: {
                    required: "Please enter fee",
                    digits: "Fee must only be digits",
                },
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
