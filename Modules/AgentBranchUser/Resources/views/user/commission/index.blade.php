@extends('agentbranchuser::user.layouts.master')
@section('title', 'Agent Branch User | Commission')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Commission</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch-user.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Commission</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Student Commissions</h3>
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
                                    <td>{{ $value->branch_commission_percent }}</td>
                                    <td>{{ $value->branch_commission_amount }}</td>
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