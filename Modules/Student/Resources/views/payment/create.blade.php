@extends('user::layouts.master')
@section('title', 'Admin | Add Payment')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.payment.index', $student->id) }}">Payments</a></li>
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
        <form id="addstudentpayment" action="{{ route('admin.student.payment.store', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Create {{ userName('Student', $student->id) }}'s Payment</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="intake_course_id">Intake Course</label> <span class="required">*</span>
                                <select class="form-control" name="intake_course_id" id="intake_course_id" required>
                                    <option value="">-- Select Intake Course --</option>
                                    @foreach($intake_courses as $course)
                                    <option value="{{ $course->intake_course_id }}">{{ $course->intakeCourse->course->course_name }} ({{ $course->intakeCourse->intake->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total_amount">Total Amount</label> <span class="required">*</span>
                                <input type="text" name="total_amount" class="form-control" id="total_amount" placeholder="Enter Total Amount">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="paid_amount">Paid Amount</label> <span class="required">*</span>
                                        <input type="text" name="paid_amount" class="form-control" id="paid_amount" placeholder="Enter Paid Amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="received_date">Received Date</label> <span class="required">*</span>
                                        <input type="date" name="received_date" class="form-control" id="received_date" placeholder="Enter Received Date" value="{{ $today }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="paid_date">Actual Paid Date</label> <span class="required">*</span>
                                        <input type="date" name="paid_date" class="form-control" id="paid_date" placeholder="Enter Actual Paid Date" value="{{ $today }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="taxable_amount">Taxable Amount</label> <span class="required">*</span>
                                        <input type="text" name="taxable_amount" class="form-control" id="taxable_amount" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="remaining_amount">Remaining Amount</label> <span class="required">*</span>
                                        <input type="text" name="remaining_amount" class="form-control" id="remaining_amount" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type</label> <span class="required">*</span>
                                        <select class="form-control" name="payment_type" id="payment_type" required>
                                            <option value="">-- Select Payment Type --</option>
                                            @foreach($paymentTypes as $payment)
                                            <option value="{{ $payment->name }}">{{ $payment->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comment">Comments</label>
                                <textarea name="comment" id="comment" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="receipt">Upload Receipt</label>
                                <input type="file" name="receipt" class="form-control" id="receipt" placeholder="Choose Receipt">
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="radio1" name="net_gross" value="net" checked onclick="yesnoCheck(this);">Net
                                <label class="form-check-label" for="radio1"></label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="radio2" name="net_gross" value="gross" onclick="yesnoCheck(this);">Gross
                                <label class="form-check-label" for="radio2"></label>
                            </div>
                            <div class="col-md-12" id="ifYes" style="display:block;">
                                <div class=" row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="agent_commission_percent">Agent Commision Percent</label>
                                            <input type="number" name="agent_commission_percent" class="form-control" id="agent_commission_percent" value="{{ $agent_commission}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="agent_commission_amount">Agent Commision Amount</label>
                                            <input type="number" name="agent_commission_amount" class="form-control" id="agent_commission_amount" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gst_percent">GST Percent</label>
                                            <input type="number" name="gst_percent" class="form-control" id="gst_percent" value="10">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gst">GST</label>
                                            <input type="number" name="gst" class="form-control" id="gst" readonly>
                                        </div>
                                    </div>
                                    <input type="hidden" name="branch_commission_percent" class="form-control" id="branch_commission_percent" value="{{ $branch_commission}}" readonly>
                                    <input type="hidden" name="branch_commission_amount" class="form-control" id="branch_commission_amount" readonly>
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
        $('#addstudentpayment').validate({
            rules: {
                total_amount: {
                    required: true
                },
                paid_amount: {
                    required: true
                },
                received_date: {
                    required: true
                },
                paid_date: {
                    required: true
                },
                payment_type: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                total_amount: "Please enter total amount",
                paid_amount: "Please enter paid amount",
                received_date: "Please enter received date",
                paid_date: "Please enter paid date",
                payment_type: "Please select one payment type",
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

    $('#intake_course_id').change(function() {
        var id = $(this).val();
        var sid = "{{ $student->id }}";
        console.log('sid:', sid);
        url = "{{url('admin/student/payment/remaining/amount')}}?intake_course_id=" + id + "&student_id=" + sid,
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if (response != null) {
                        $('#total_amount').val(response);
                    }
                }
            });
    });

    // function yesnoCheck(that) {
    //     if (that.value == "gross") {
    //         console.log("gross: ", "ifYes");
    //         document.getElementById("ifYes").style.display = "block";
    //     } else {
    //         console.log("net: ", "ifYes");
    //         document.getElementById("ifYes").style.display = "none";
    //     }
    // }

    document.getElementById("paid_amount").addEventListener("keyup", checkPaidAmount);

    function checkPaidAmount() {
        var ta = document.getElementById('total_amount');
        var pa = document.getElementById('paid_amount');

        if (parseFloat(pa.value) == 0) {
            alert('Paid Amount cannot be 0')
            return false;
        } else if (parseFloat(ta.value) == 0) {
            alert('Total Amount cannot be 0')
            return false;
        } else if (parseFloat(ta.value) >= parseFloat(pa.value)) {
            var rem = ta.value - pa.value;
            console.log('Remaining: ', rem);
            document.getElementById('remaining_amount').value = rem;

            var tax = pa.value;
            document.getElementById('taxable_amount').value = tax;
            console.log('Taxable: ', tax);

            var agent_tax = document.getElementById('agent_commission_percent').value;
            var at = parseFloat(agent_tax);
            var ata = (parseFloat(pa.value) / 100) * at;
            console.log('ATA', ata);
            var aca = document.getElementById('agent_commission_amount').value = ata;

            var gst_percent = document.getElementById('gst_percent').value;
            var gstp = parseInt(gst_percent);
            var gst = parseInt((aca / 100) * gstp);
            var gsta = document.getElementById('gst').value = gst;

            var branch_tax = document.getElementById('branch_commission_percent').value;
            var bt = parseFloat(branch_tax);
            var bta = parseInt((aca / 100) * bt);
            document.getElementById('branch_commission_amount').value = bta;

            document.getElementById("agent_commission_percent").addEventListener("keyup", checkAgentCommission);

            function checkAgentCommission() {
                var agent_tax_check = document.getElementById('agent_commission_percent');
                var atc = parseFloat(agent_tax_check.value);
                if (atc == 0) {
                    document.getElementById('agent_commission_amount').value = 0;
                    document.getElementById('gst_percent').value = 0;
                    document.getElementById('gst').value = 0;
                    document.getElementById('branch_commission_percent').value = 0;
                    document.getElementById('branch_commission_amount').value = 0;
                } else if (atc > 100) {
                    alert('Percentange cannot be more than 100')
                } else {
                    var atca = (parseFloat(pa.value) / 100) * atc;
                    console.log('ATCA', atca);
                    var acac = document.getElementById('agent_commission_amount').value = atca;

                    var gst_percent_ac = document.getElementById('gst_percent').value;
                    var gstpac = parseInt(gst_percent_ac);
                    var gstac = parseInt((acac / 100) * gstpac);
                    var gstaac = document.getElementById('gst').value = gstac;

                    var branch_tax_ac = document.getElementById('branch_commission_percent').value;
                    var bt_ac = parseFloat(branch_tax_ac);
                    var bta_ac = parseInt((acac / 100) * bt_ac);
                    document.getElementById('branch_commission_amount').value = bta_ac;
                }
            }

            document.getElementById("gst_percent").addEventListener("keyup", checkGst);

            function checkGst() {
                var gst_percent_check = document.getElementById('gst_percent').value;
                var acag = document.getElementById('agent_commission_amount').value;
                var gstpc = parseInt(gst_percent_check);
                var gstg = parseInt((acag / 100) * gstpc);
                var gstag = document.getElementById('gst').value = gstg;
            }

        } else if (parseFloat(ta.value) < parseFloat(pa.value)) {
            alert('Paid Amount cannot be greater than Total Amount')
            return false;
        }
    }
</script>
@endsection