@extends('user::layouts.master')
@section('title', 'Admin | Agent Commissions')

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
                <h1>Agent Commissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agent.index') }}">Agents</a></li>
                    <li class="breadcrumb-item active">Commissions</li>
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
                        <h3 class="card-title">List of {{ $agent->name }}'s Commissions</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($commissions) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Installment</th>
                                    <th>Commission %</th>
                                    <th>Amount</th>
                                    <th>Paid Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $value->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->student_id) }}</td>
                                    <td>{{ $value->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->studentIntakeCourseFeeInstallment->studentIntakeCourseFee->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->studentIntakeCourseFeeInstallment->name }}</td>
                                    <td>{{ $value->agent_commission_percent }}</td>
                                    <td>{{ $value->agent_commission_amount }}</td>
                                    <td>
                                        @if ($value->paid_to_agent == 1)
                                        @if ($value->agentCommissionPayment)
                                        <a href="{{ route('admin.agent.commission.receipt', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-money-bill"></i> Receipt</a>
                                        @else Paid @endif
                                        @else 
                                        <a href="{{ route('admin.agent.commission.pay', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-money-bill"></i> Pay</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Installment</th>
                                    <th>Commission %</th>
                                    <th>Amount</th>
                                    <th>Paid Status</th>
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