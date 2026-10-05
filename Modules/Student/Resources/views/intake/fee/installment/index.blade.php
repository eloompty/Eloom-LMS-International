@extends('user::layouts.master')
@section('title', 'Admin | Student Fee Installment')

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
                <h1>Student Fee Installment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $student_intake_course_fee->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.fee.index', $student_intake_course->id) }}">Fees</a></li>
                    <li class="breadcrumb-item active">Intallments</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of {{ userName('Student', $student_intake_course_fee->student_id) }}'s Fee Installment</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.intake.course.fee.installment.edit', $student_intake_course_fee->id) }}" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</a></div>
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
                        @if(count($installments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Enrollment Fee</th>
                                    <th>Material Fee</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($installments as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->enrollment_fee }}</td>
                                    <td>{{ $value->material_fee }}</td>
                                    <td>{{ $value->amount }}</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>{{ studentPaymentStatus($value->status) }}</td>
                                    <td>
                                        @if ($value->status == 1)
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-xl{{$value->id}}">
                                            Pay
                                        </button>
                                        <div class="modal fade" id="modal-xl{{$value->id}}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $value->name }} Payment</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form id="pay" action="{{ route('admin.student.intake.course.fee.installment.payment', $value->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="total_amount">Total Amount</label> <span class="required">*</span>
                                                                        <input type="text" name="total_amount" class="form-control" id="total_amount{{ $index }}" value="{{ $value->amount }}" readonly required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paid_amount">Paid Amount</label> <span class="required">*</span>
                                                                                <input type="text" name="paid_amount" class="form-control" id="paid_amount{{ $index }}" placeholder="Enter Paid Amount" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="received_date">Received Date</label> <span class="required">*</span>
                                                                                <input type="date" name="received_date" class="form-control" id="received_date" placeholder="Enter Received Date" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paid_date">Actual Paid Date</label> <span class="required">*</span>
                                                                                <input type="date" name="paid_date" class="form-control" id="paid_date" placeholder="Enter Actual Paid Date" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="taxable_amount">Taxable Amount</label> <span class="required">*</span>
                                                                        <input type="text" name="taxable_amount" class="form-control" id="taxable_amount{{ $index }}" readonly required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="remaining_amount">Remaining Amount</label> <span class="required">*</span>
                                                                        <input type="text" name="remaining_amount" class="form-control" id="remaining_amount{{ $index }}" readonly required>
                                                                    </div>
                                                                </div>
                                                                @if ($value->enrollment_fee > 0)
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="enrollment_fee">Enrollment Fee</label> <span class="required">*</span>
                                                                        <input type="text" name="enrollment_fee" class="form-control" id="enrollment_fee" placeholder="Enter Enrollment Fee" value="{{ $value->enrollment_fee }}" readonly required>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @if ($value->material_fee > 0)
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="material_fee">Material Fee</label> <span class="required">*</span>
                                                                        <input type="text" name="material_fee" class="form-control" id="material_fee" placeholder="Enter Material Fee" value="{{ $value->material_fee }}" readonly required>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="payment_type">Payment Type</label> <span class="required">*</span>
                                                                        <select class="form-control" name="payment_type" id="payment_type" required>
                                                                            <option value="">-- Select Payment Type --</option>
                                                                            @foreach($payments as $key => $value)
                                                                            <option value="{{ $value->name }}">{{ $value->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="comments">Comments</label> <span class="required">*</span>
                                                                        <textarea name="comments" id="comments" class="form-control" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="receipt">Upload Receipt</label> <span class="required">*</span>
                                                                        <input type="file" name="receipt" class="form-control" id="receipt" placeholder="Choose Receipt" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="agent_commission_percent">Agent Commision Percent</label>
                                                                                <input type="text" name="agent_commission_percent" class="form-control" id="agent_commission_percent" value="{{ $agent_commission}}" readonly required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="agent_commission_amount">Agent Commision Amount</label>
                                                                                <input type="text" name="agent_commission_amount" class="form-control" id="agent_commission_amount{{ $index }}" readonly required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="paid_to_agent">Agent Commision Paid</label>
                                                                                <input type="checkbox" name="paid_to_agent" id="paid_to_agent{{ $index }}" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="gst">GST (10%)</label>
                                                                                <input type="text" name="gst" class="form-control" id="gst{{ $index }}" readonly required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="gst_comission">GST and Commission</label>
                                                                                <input type="text" name="gst_commission" class="form-control" id="gst_comission{{ $index }}" readonly required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="gst_waiver">GST Waiver</label>
                                                                                <input type="checkbox" name="gst_waiver" id="gst_waiver{{ $index }}" class="form-control" onclick="onGSTWaived();" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="branch_commission_percent">Branch Commision Percent</label>
                                                                                <input type="text" name="branch_commission_percent" class="form-control" id="branch_commission_percent" value="{{ $branch_commission}}" readonly required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="branch_commission_amount">Branch Commision Amount</label>
                                                                                <input type="text" name="branch_commission_amount" class="form-control" id="branch_commission_amount{{ $index }}" value="" readonly required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        @else
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-paid{{ $value->id }}">
                                            Receipt
                                        </button>
                                        <div class="modal fade" id="modal-paid{{ $value->id }}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $value->name }} Receipt</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table style="width:100%">
                                                                    <tr>
                                                                        <th>Total Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->total_amount }}</td>
                                                                    </tr>
                                                                    @if ($value->enrollment_fee > 0)
                                                                    <tr>
                                                                        <th>Enrollment Fee</th>
                                                                        <td>{{ $value->enrollment_fee }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    @if ($value->material_fee > 0)
                                                                    <tr>
                                                                        <th>Material Fee</th>
                                                                        <td>{{ $value->material_fee }}</td>
                                                                    </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <th>Paid Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->paid_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Remaining Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->remaining_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Taxable Amount</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->taxable_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Payment Type</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->payment_type }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Agent Commission</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->agent_commission_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Paid to Agent</th>
                                                                        <td>@if ($value->studentIntakeCourseFeePayment->paid_to_agent == 1 ) Paid @else Remaining @endif</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>GST (10%)</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->gst }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>GST Waiver</th>
                                                                        <td>@if ($value->studentIntakeCourseFeePayment->gst_waiver == 1) True @else False @endif </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Branch Commission</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->branch_commission_amount }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Reciept</th>
                                                                        <td><img src="{{ asset($value->studentIntakeCourseFeePayment->receipt) }}" alt="" width="200" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Notes</th>
                                                                        <td>{{ $value->studentIntakeCourseFeePayment->paymentnotes->first()->notes }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        @endif
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Enrollment Fee</th>
                                    <th>Material Fee</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
@foreach($installments as $index => $value)
<script>
    document.getElementById("paid_amount{{ $index }}").addEventListener("keyup", checkPaidAmount);
    document.getElementById("gst_waiver{{ $index }}").addEventListener("onchange", onGSTCheck);

    var agentComissionAmt = 0;
    var oldGSTNComission = 0;

    function checkPaidAmount() {
        var ta = document.getElementById('total_amount{{ $index }}');
        var pa = document.getElementById('paid_amount{{ $index }}');

        if (parseFloat(ta.value) >= parseFloat(pa.value)) {
            var new_pa = document.getElementById('paid_amount{{ $index }}');
            var rem = ta.value - new_pa.value;
            console.log('Remaining: ', rem);
            document.getElementById('remaining_amount{{ $index }}').value = rem;

            var tax = new_pa.value;
            document.getElementById('taxable_amount{{ $index }}').value = tax;
            console.log('Taxable: ', tax);

            var agent_tax = document.getElementById('agent_commission_percent').value;
            var at = parseFloat(agent_tax);
            var ata = (parseFloat(new_pa.value) / 100) * at;
            console.log('ATA', ata);
            var aca = document.getElementById('agent_commission_amount{{ $index }}').value = ata;
            agentComissionAmt = aca;
            var gstw = document.getElementById('gst_waiver{{ $index }}').value;

            if (gstw == 1) {
                var gst = 0
            } else {
                var gst = parseInt((aca / 100) * 10);
            }

            var gsta = document.getElementById('gst{{ $index }}').value = gst;

            var gstc = aca + gst;
            var oldGSTNComission = gstc;
            document.getElementById('gst_comission{{ $index }}').value = gstc;

            var branch_tax = document.getElementById('branch_commission_percent').value;
            var bt = parseFloat(branch_tax);
            var bta = parseInt((aca / 100) * bt);
            document.getElementById('branch_commission_amount{{ $index }}').value = bta;
            console.log("smaller");
        } else {
            console.log("greater");
            alert('Paid Amount cannot be greater than Total Amount')
            return false;
        }

        function onGSTCheck(checked) {
            var elm = document.getElementById('gst_waiver{{ $index }}');
            if (checked != elm.checked) {
                elm.click();
            }
        }

        const checkbox = document.getElementById('gst_waiver{{ $index }}');
        checkbox.addEventListener('change', (event) => {
            if (event.currentTarget.checked) {
                document.getElementById('gst{{ $index }}').value = 0;
                document.getElementById('gst_comission{{ $index }}').value = document.getElementById('agent_commission_amount{{ $index }}').value;
            } else {
                document.getElementById('gst{{ $index }}').value = parseInt((agentComissionAmt / 100) * 10);
                document.getElementById('gst_comission{{ $index }}').value = oldGSTNComission;
                // document.getElementById('gst_comission{{ $index }}') = document.getElementById('agent_commission_amount{{ $index }}').value
            }
            
        })
    }
</script>
@endforeach
@endsection