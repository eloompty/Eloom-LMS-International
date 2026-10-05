@extends('user::layouts.master')
@section('title', 'Admin | Edit Unit Fee')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Unit Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    li class="breadcrumb-item"><a href="{{ route('admin.student.fee.index', $fee->student_id) }}">Fees</a></li>
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
        <form id="unitfee" action="{{ route('admin.student.fee.unit.update', $fee->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ userName('Student', $fee->student_id) }}'s Unit Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Fee name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Fee name" value="{{ $fee->name }}">
                            </div>
                            <div class="form-group">
                                <label for="fee">Fee Amount</label> <span class="required">*</span>
                                <input type="text" name="fee" class="form-control" id="fee" placeholder="Enter Fee Amount" value="{{ $fee->fee }}">
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label> <span class="required">*</span>
                                <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $fee->due_date }}">
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
        $('#unitfee').validate({
            rules: {
                name: {
                    required: true,
                },
                fee: {
                    required: true,
                    digits: true,
                },
                due_date: {
                    required: true
                },
            },
            messages: {
                name: "Please enter fee name",
                fee: {
                    required: "Please enter fee amount",
                    digits: "Fee amount can only be number",
                },
                due_date: "Please select one due date",

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
