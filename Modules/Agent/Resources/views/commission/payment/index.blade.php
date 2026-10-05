@extends('user::layouts.master')
@section('title', 'Admin | Pay Agent Commission')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pay Agent</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.index') }}">Agents</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.commission.index', $agent_id) }}">Commissions</a></li>
                    <li class="breadcrumb-item active">Pay</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="pay" action="{{ route('admin.agent.commission.payment', $commission->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Pay</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="intake">Intake</label>
                                    <input type="text" name="intake" class="form-control" id="intake" value="{{ $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->intakeCourse->intake->name }}" readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="course">Course</label>
                                    <input type="text" name="course" class="form-control" id="course" value="{{ $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->intakeCourse->course->course_name }}" readonly>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="student">Student</label>
                                    <input type="text" name="student" class="form-control" id="student" value="{{ userName('Student', $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student_id) }}" readonly>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="installment">Installment</label>
                                    <input type="text" name="installment" class="form-control" id="installment" value="{{ $commission->studentIntakeCourseFeeInstallment->name }}" readonly>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount" class="form-control" id="amount" readonly value="{{ $commission->agent_commission_amount }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="payment_mode">Payment Mode</label> <span class="required">*</span>
                                    <select name="payment_mode" class="form-control" id="payment_mode">
                                        <option value="" selected disabled>-- Select Payment Mode --</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Deposit">Bank Deposit</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="paid_date">Paid Date</label> <span class="required">*</span>
                                    <input type="date" name="paid_date" class="form-control" id="paid_date" placeholder="Enter Paid Date">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="remarks">Remarks</label> <span class="required">*</span>
                                    <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10" placeholder="Enter Remarks"></textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="image">Receipt</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                </div>
                            </div>
                            <input type="hidden" name="agent_id" class="form-control" id="agent_id" value="{{ $agent_id }}">
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
        $('#pay').validate({
            rules: {
                payment_mode: {
                    required: true,
                },
                paid_date: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
                image: {
                    required: true,
                },
            },
            messages: {
                payment_mode: "Please select one payment mode",
                paid_date: "Please enter paid date",
                remars: "Please enter remarks",
                image: "Please upload receipt",
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