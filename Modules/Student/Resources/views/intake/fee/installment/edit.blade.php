@extends('user::layouts.master')
@section('title', 'Admin | Edit Installment')

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
                <h1>Edit Installment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $student_intake_course_fee->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.fee.index', $student_intake_course->id) }}">Fees</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.fee.installment.index', $student_intake_course_fee->student_id) }}">Installments</a></li>
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
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Total Fee Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Fee</label>
                                    <input type="text" class="form-control" value="{{ $fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Initial Fee</label>
                                    <input type="text" class="form-control" value="{{ $student_intake_course_fee->initial_fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Enrollment Fee</label>
                                    <input type="text" class="form-control" value="{{ $enrollment_fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Material Fee</label>
                                    <input type="text" class="form-control" value="{{ $material_fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Total Fee</label>
                                    <input type="text" class="form-control" value="{{ $total_fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Remaining</label>
                                    <input type="text" class="form-control" value="{{ $remaining }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" class="form-control" value="{{  dateFormat($student_intake_course_fee->intakeCourse->starting_date) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" class="form-control" value="{{  dateFormat($student_intake_course_fee->intakeCourse->ending_date) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Current Total</label>
                                    <input type="text" name="total" id="total" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <input type="text" class="form-control" value="{{ $paid_installment }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Remaining Payment</label>
                                    <input type="text" class="form-control" value="{{ $remaining_installment }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- form start -->
        <form id="editfee" action="{{ route('admin.student.intake.course.fee.installment.update', $student_intake_course_fee->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Installment</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @foreach($installments as $index => $value)
                            <div class="row" id="row">
                                <input type="hidden" name="installment_id[]" value="{{ $value->id }}">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Name</label> <span class="required">*</span>
                                        <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ $value->name }}" @if ($value->status != 1) readonly @endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label> <span class="required">*</span>
                                        <input type="number" step="0.01" name="amount[]" class="form-control" id="amount" oninput="findTotal(this)" placeholder="Enter Amount" value="{{ $value->amount }}" @if ($value->status != 1) readonly @endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label> <span class="required">*</span>
                                        <input type="date" name="due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $value->due_date }}" @if ($value->status != 1) readonly @endif>
                                    </div>
                                </div>
                                @if ($value->status == 1)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="button"></label>
                                        <div class="input-group-prepend">
                                            <button class="btn btn-danger" id="DeleteRow" type="button">
                                                <i class="bi bi-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                            <div id="newinput"></div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <button id="rowAdder" type="button" class="btn btn-dark">
                                        <span class="bi bi-plus-square-dotted">
                                        </span> ADD Installment
                                    </button>
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
        $('#editfee').validate({
            rules: {
                "name[]": {
                    required: true,
                },
                "amount[]": {
                    required: true,
                },
                "due_date[]": {
                    required: true,
                },
            },
            messages: {
                "name[]": "Please enter unit name",
                "amount[]": "Please enter amount",
                "due_date[]": "Please enter due date",
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

    $("#rowAdder").click(function() {
        newRowAdd =
            '<div class="row" id="row"><input type="hidden" name="installment_id[]">' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="name">Name</label> <span class="required">*</span>' +
            '<input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name"></div></div>' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="amount">Amount</label> <span class="required">*</span>' +
            '<input type="number" step="0.01" name="amount[]" class="form-control" id="amount" placeholder="Enter Amount"></div></div>' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="due_date">Due Date</label> <span class="required">*</span>' +
            '<input type="date" name="due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date"></div></div>' +
            '<div class="col-md-3"><div class="form-group"><label for="button"></label><div class="input-group-prepend">' +
            '<button class="btn btn-danger" id="DeleteRow" type="button"><i class="bi bi-trash"></i>Delete</button></div></div></div></div>'

        $('#newinput').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
    })

    function findTotal(e) {
        var arr = document.getElementsByName('amount[]');
        var rem = parseInt('{{ $remaining_amount }}');
        var tot = 0;
        for (var i = 0; i < arr.length; i++) {
            if (parseInt(arr[i].value))
                tot += parseInt(arr[i].value);
        }
        total = tot - rem;
        console.log('Current Total', total);
        document.getElementById('total').value = total;
    }

    findTotal();

</script>
@endsection