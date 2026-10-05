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
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeCourseFee->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.fee.index', $intakeCourseFee->intake_course_id) }}">Fees</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.fee.installment.index', $intakeCourseFee->id) }}">Installments</a></li>
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
                                    <label>Total Fee</label>
                                    <input type="text" class="form-control" value="{{ $total_fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Total Semester Fee</label>
                                    <input type="text" class="form-control" value="{{ $total_intsallment }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Remaining Setup</label>
                                    <input type="text" class="form-control" value="{{ $remaining }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" class="form-control" value="{{  dateFormat($intakeCourseFee->intakeCourse->starting_date) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" class="form-control" value="{{  dateFormat($intakeCourseFee->intakeCourse->ending_date) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Current Total</label>
                                    <input type="text" name="total" id="total" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- form start -->
        <form id="editfee" action="{{ route('admin.intake.course.fee.installment.update', $intakeCourseFee->id) }}" method="POST">
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
                                        <input type="text" name="name[]" class="form-control" id="name" placeholder="Enter Name" value="{{ $value->name }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label> <span class="required">*</span>
                                        <input type="number" step="0.01" name="amount[]" class="form-control" id="amount" oninput="findTotal(this)" placeholder="Enter Amount" value="{{ $value->amount }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label> <span class="required">*</span>
                                        <input type="date" name="due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $value->due_date }}">
                                    </div>
                                </div>
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
                                @if ($value->intakeCourseFeeInstallments->count() > 0)
                                @foreach($value->intakeCourseFeeInstallments as $fee_installment)
                                <div class="row" id="extra{{ $value->id }}">
                                    <input type="hidden" name="extra_installment_id[]" value="{{ $fee_installment->id }}">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="name">Name</label> <span class="required">*</span>
                                            <input type="text" name="extra_name]" class="form-control" id="name" placeholder="Enter Name" value="{{ $fee_installment->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="amount">Amount</label> <span class="required">*</span>
                                            <input type="number" step="0.01" name="extra_amount[]" class="form-control" id="amount" placeholder="Enter Amount" value="{{ $fee_installment->amount }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="due_date">Due Date</label> <span class="required">*</span>
                                            <input type="date" name="extra_due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date" value="{{ $fee_installment->due_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="button"></label>
                                            <div class="input-group-prepend">
                                                <button class="btn btn-danger" id="DeleteRowExtra{{ $value->id }}" type="button">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <div id="newinputExtra{{ $value->id }}"></div>
                                <div class="form-group col-md-3">
                                    <!-- <div class="col-md-5"> -->
                                    <button id="rowAdderExtra{{ $value->id }}" type="button" class="btn btn-success">
                                        <span class="bi bi-plus-square-dotted">
                                        </span> ADD Extra Fee
                                    </button>
                                    <!-- </div> -->
                                </div>
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
    var lastID = "{{$installments->last()->id}}";
    var valueID = Number(lastID)
    console.log("Installments count last id: ", valueID);
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
        // var valueForRow = Number(valueID);
        var newID = valueID + 1;
        console.log(newID);
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
            '<button class="btn btn-danger" id="DeleteRow" type="button"><i class="bi bi-trash"></i>Delete</button></div></div></div>' +
            '<div id="newinputExtra' + newID + '"></div> <div class="form-group">' +
            '<button id="rowAdderExtra' + newID + '" type="button" onClick=addExtraRow(' + newID + ') class="btn btn-success"> <span class="bi bi-plus-square-dotted">' +
            '</span> ADD Extra Fee</button> </div></div></div>'
        $('#newinput').append(newRowAdd);
        valueID++;
    });

    $("body").on("click", "#DeleteRow", function() {
        console.log("extra delete id");
        console.log("#DeleteRowExtra{{ $value->id }}");
        console.log("extra row id");
        console.log("#extra{{ $value->id }}");
        $(this).parents("#row").remove();
        $(this).parents("#extra{{ $value->id }}").remove();
        $(this).parents("#newinputExtra{{ $value->id }}").remove();
    })

    function addExtraRow(id) {
        console.log('button has been clicked');
        console.log(id);
        newRowAdd =
            '<div class="row col-md-12" id="extra' + id + '"><input type="hidden" name="extra_installment_id[]">' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="name">Name</label> <span class="required">*</span>' +
            '<input type="text" name="extra_name[]" class="form-control" id="name" placeholder="Enter Name"></div></div>' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="amount">Amount</label> <span class="required">*</span>' +
            '<input type="number" step="0.01" name="extra_amount[]" class="form-control" id="amount" placeholder="Enter Amount"></div></div>' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="due_date">Due Date</label> <span class="required">*</span>' +
            '<input type="date" name="extra_due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date"></div></div>' +
            '<div class="col-md-3"><div class="form-group"><label for="button"></label><div class="input-group-prepend">' +
            '<button onClick=deleteExtraRow(' + id + ') class="btn btn-danger" id="DeleteRowExtra' + id + '" type="button"><i class="fa fa-trash"></i></button></div></div></div></div>'
        $('#newinputExtra' + id + '').append(newRowAdd);

        $("body").on("click", "#DeleteRowExtra" + id, function() {
            $(this).parents("#extra" + id).remove();
        })
    }

    function findTotal(e) {
        var arr = document.getElementsByName('amount[]');
        var tot = 0;
        for (var i = 0; i < arr.length; i++) {
            if (parseInt(arr[i].value))
                tot += parseInt(arr[i].value);
        }
        console.log('Current Total', tot);
        document.getElementById('total').value = tot;
    }

    findTotal();
</script>

@foreach($installments as $index => $value)
<script>
    $("#rowAdderExtra{{ $value->id }}").click(function() {
        newRowAdd =
            '<div class="row col-md-12" id="extra{{ $value->id }}"><input type="hidden" name="extra_installment_id[]">' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="name">Name</label> <span class="required">*</span>' +
            '<input type="text" name="extra_name[]" class="form-control" id="name" placeholder="Enter Name"></div></div>' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="amount">Amount</label> <span class="required">*</span>' +
            '<input type="number" step="0.01" name="extra_amount[]" class="form-control" id="amount" placeholder="Enter Amount"></div></div>' +
            '<div class="col-md-3"><div class="form-group">' +
            '<label for="due_date">Due Date</label> <span class="required">*</span>' +
            '<input type="date" name="extra_due_date[]" class="form-control" id="due_date" placeholder="Enter Due Date"></div></div>' +
            '<div class="col-md-3"><div class="form-group"><label for="button"></label><div class="input-group-prepend">' +
            '<button class="btn btn-danger" id="DeleteRowExtra{{ $value->id }}" type="button"><i class="fa fa-trash"></i></button></div></div></div></div>'

        $('#newinputExtra{{ $value->id }}').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRowExtra{{ $value->id }}", function() {
        $(this).parents("#extra{{ $value->id }}").remove();
    })
</script>
@endforeach
@endsection
