@extends('user::layouts.master')
@section('title', 'Admin | Commission Receipt')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Commission Receipt</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.commission.index', $agent_id) }}">Commissions</a></li>
                    <li class="breadcrumb-item active">Receipt</li>
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
                        <h3 class="card-title">Payment Receipt</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table style="width:100%" id="printTable" class="table table-striped table-bordered table-hover" border="2">
                            <tr>
                                <th>Agent</th>
                                <td>
                                    {{ $commission->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student->studentAgent->agent->company_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td>{{ $commission->agent_commission_amount }}</td>
                            </tr>
                            <tr>
                                <th>Paid Date</th>
                                <td>{{ dateFormat($commission->paid_date) }}</td>
                            </tr>

                            <tr>
                                <th>Reciept</th>
                                <td><img src="{{ asset($commission->agentCommissionPayment->receipt) }}" alt="" width="200" /></td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>
                                    {{ $commission->agentCommissionPayment->remarks }}
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