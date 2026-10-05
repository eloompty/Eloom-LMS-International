@extends('user::layouts.master')
@section('title', 'Admin | Payments')

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
                <h1>Payments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Payments</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }}'s Payments</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.payment.create', $student->id) }}" class="btn btn-success">Add Payment</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($payments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Total Due Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Paid Date</th>
                                    <th>Payment Type</th>
                                    <th>Commission Payable</th>
                                    <th>Remaining Amount</th>
                                    <th>Commission Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->total_amount }}</td>
                                    <td>{{ $value->paid_amount }}</td>
                                    <td data-sort='{{ convertDate($value->paid_date) }}'>{{ dateFormat($value->paid_date) }}</td>
                                    <td>@if ($value->paid_to_agent == 1) Net @else Gross @endif</td>
                                    <td>@if ($value->paid_to_agent == 1) 0 @else {{ $value->agent_commission_amount }} @endif</td>
                                    <td>{{ $value->remaining_amount }}</td>
                                    <td>
                                        @if ($value->agentCommission == false && $value->paid_to_agent == 0) <a href="{{ route('admin.student.payment.commission.pay', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-money-bill"></i> Pay Commission</a>
                                        @elseif ($value->agentCommission == true) <a href="{{ route('admin.student.payment.commission.receipt', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-money-bill"></i> Receipt</a> @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.payment.show', $value->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Show</a>
                                        <a href="{{ route('admin.student.payment.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pen"></i> Edit</a>
                                        <a href="{{ route('admin.student.payment.print', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-file-pdf"></i> Print</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <th>Total Due Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Paid Date</th>
                                    <th>Payment Type</th>
                                    <th>Commission Payable</th>
                                    <th>Remaining Amount</th>
                                    <th>Commission Status</th>
                                    <th>Action</th>
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