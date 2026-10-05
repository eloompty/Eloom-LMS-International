@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Edit Student Group')

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
                <h1>Edit Student Group</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.onlineclass.group.index') }}">Group Online Classes</a></li>
                    <li class="breadcrumb-item active">Edit Student Group</li>
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
                        <h3 class="card-title">Edit <small>Student Group</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addonlineclass" action="{{ route('trainer.onlineclass.group.update', $trainerGroup->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $trainerGroup->group->name }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($trainerGroup->status == '1')selected @endif value="1">Active</option>
                                    <option @if($trainerGroup->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="student_id">List of Students</label> <span class="required">*</span>
                                @foreach($students as $index => $value)
                                <div class="row">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" class="checkbox" id="{{ $index }}" name="student_id[]" value="{{ $value->student_id }}" checked disabled>
                                        <label for="{{ $index }}" class="check view">{{ userName('Student', $value->student_id) }}</label>
                                    </div>
                                </div>
                                @endforeach
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
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
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
        $('#addonlineclass').validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true,
                },
                "student_id[]": {
                    required: true,
                }
            },
            messages: {
                name: "Please enter name",
                status: "Please choose one status",
                "student_id[]": "Please select students",
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