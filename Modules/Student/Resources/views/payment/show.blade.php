@extends('user::layouts.master')
@section('title', 'Admin | Show Payment')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Show Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.payment.index', $payment->student_id) }}">Payments</a></li>
                    <li class="breadcrumb-item active">Show</li>
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
                        <h3 class="card-title">{{ userName('Student', $payment->student_id) }}'s Payments</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table style="width:100%" id="printTable" class="table table-striped table-bordered table-hover" border="2">
                            <tr>
                                <th>Intake</th>
                                <td>{{ $payment->intakeCourse->course->course_name }} ({{ $payment->intakeCourse->intake->name }})</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>{{ $payment->total_amount }}</td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>{{ $payment->paid_amount }}</td>
                            </tr>
                            <tr>
                                <th>Agent Commission ({{ $payment->agent_commission_percent }})%</th>
                                <td>{{ $payment->agent_commission_amount }}</td>
                            </tr>
                            <tr>
                                <th>GST ({{ $payment->gst_percent }})%</th>
                                <td>{{ $payment->gst }}</td>
                            </tr>
                            <tr>
                                <th>Paid to Agent</th>
                                <td>@if ($payment->paid_to_agent == 1 ) Yes @elseif ($payment->agentCommission != NULL) Yes @else Remaining @endif</td>
                            </tr>
                            <tr>
                                <th>Total Received Net</th>
                                <td>{{ $payment->paid_amount - $payment->agent_commission_amount - $payment->gst }}</td>
                            </tr>
                            <tr>
                                <th>Received Date</th>
                                <td>{{ dateFormat($payment->received_date) }}</td>
                            </tr>
                            <tr>
                                <th>Actual Paid Date</th>
                                <td>{{ dateFormat($payment->paid_date) }}</td>
                            </tr>
                            <tr>
                                <th>Remaining Amount</th>
                                <td>{{ $payment->remaining_amount }}</td>
                            </tr>
                            <tr>
                                <th>Payment Type</th>
                                <td>{{ $payment->payment_type }}</td>
                            </tr>
                            <tr>
                                <th>Reciept</th>
                                <td><img src="{{ asset($payment->receipt) }}" alt="" width="200" /></td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>
                                    {{ $payment->comment }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection