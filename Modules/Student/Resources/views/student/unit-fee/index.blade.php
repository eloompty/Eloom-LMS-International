@extends('student::student.layouts.master')
@section('title', 'Student | Unit Fee')

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
                <h1>Unit Fees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Unit Fees</li>
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
                        <h3 class="card-title">List of Unit Fees</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($fees) > 0)
                        <form role="form" action="{{ route('student.fee.unit.mutliple.payment') }}" method="post">
                            @csrf
                            <table id="example4" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Intake</th>
                                        <th>Course</th>
                                        <th>Unit</th>
                                        <th>Fee Name</th>
                                        <th>Fee Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fees as $index => $value)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $value->intakeUnit->intakeCourse->intake->name }}</td>
                                        <td>{{ $value->intakeUnit->intakeCourse->course->course_name }}</td>
                                        <td>{{ $value->intakeUnit->unit->name }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ $value->fee }}
                                            @if ($value->status == 1) 
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo{{ $value->id }}" name="fees[]" value="{{ $value->id }}">
                                                <label for="checkboxInfo{{ $value->id }}" class="check view"></label>
                                            </div>
                                            @endif
                                        </td>
                                        <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                                        <td>{{ studentPaymentStatus($value->status) }}
                                        </td>
                                        <td>
                                            @if ($value->status == 1) <a href="{{ route('student.fee.unit.payment', $value->id) }}" class="btn btn-info btn-sm"> Pay</a> @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Intake</th>
                                        <th>Course</th>
                                        <th>Unit</th>
                                        <th>Fee Name</th>
                                        <th>Fee Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="submit" class="btn btn-primary">Make Payment</button>
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