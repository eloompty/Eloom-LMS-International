@extends('user::layouts.master')
@section('title', 'Admin | Intake Fee Installments')

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
                <h1>{{ $intakeCourseFee->intakeCourse->course->course_name }}'s {{ $intakeCourseFee->name }}'s Installments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeCourseFee->intakeCourse->intake_id) }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.fee.index', $intakeCourseFee->intake_course_id) }}">Fees</a></li>
                    <li class="breadcrumb-item active">Installments</li>
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
                        <h3 class="card-title">List of {{ $intakeCourseFee->intakeCourse->course->course_name }}'s {{ $intakeCourseFee->name }}'s Installment</h3>
                        <div class="col-md-12 text-right">
                            <a href="{{ route('admin.intake.course.fee.installment.edit', $intakeCourseFee->id) }}" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</a>
                        </div>
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
                                    <label>Extra Fee Amount</label>
                                    <input type="text" class="form-control" value="{{ $total_extra_fee }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" class="form-control" value="{{ dateFormat($intakeCourseFee->intakeCourse->starting_date) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" class="form-control" value="{{ dateFormat($intakeCourseFee->intakeCourse->ending_date) }}" disabled>
                                </div>
                            </div>
                        </div>
                        @if(count($installments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($installments as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @if ($index == 0)
                                        @foreach ($intakeCourseFee->intakeCourseFeeTypes as $fee_type)
                                        <b>{{ ucwords(str_replace('_', ' ', $fee_type->key)) }}:</b> {{ $fee_type->value }} <br>
                                        @endforeach
                                        @endif
                                        @if ($value->intakeCourseFeeInstallments->count() > 0)
                                        <b>Semester Fee: </b>{{ $value->amount }}<br>
                                        @foreach($value->intakeCourseFeeInstallments as $fee_installment)
                                        <b>{{ $fee_installment->name }}: </b> {{ $fee_installment->amount }}<br>
                                        @endforeach
                                        @endif
                                        <b>Total: </b>@if($value->intakeCourseFeeInstallments->count() > 0){{ $value->extra_fee + $value->amount }} @else {{ $value->amount }} @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
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