@extends('user::layouts.master')
@section('title', 'Admin | Student Fees')

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
                <h1>Student Fees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item active">Fees</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $studentIntakeCourse->student_id) }} Fees</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.intake.course.fee.create', $studentIntakeCourse->id) }}" class="btn btn-success">Add Fee</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($fees) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Initial Fee</th>
                                    <th>Enrollment Fee</th>
                                    <th>Enrollment Fee Wavier</th>
                                    <th>Material Fee</th>
                                    <th>Material Fee Wavier</th>
                                    <th>Fee</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fees as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->initial_fee }}</td>
                                    <td>{{ $value->enrollment_fee }}</td>
                                    <td>@if ($value->enrollment_fee_wavier == 1) Yes @else No @endif</td>
                                    <td>{{ $value->material_fee }}</td>
                                    <td>@if ($value->material_fee_wavier == 1) Yes @else No @endif</td>
                                    <td>{{ $value->fee }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date == NULL) - @else {{ dateFormat($value->due_date) }} @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.intake.course.fee.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a href="{{ route('admin.student.intake.course.fee.installment.index', $value->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-money-bill"></i> Installments</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Initial Fee</th>
                                    <th>Enrollment Fee</th>
                                    <th>Enrollment Fee Wavier</th>
                                    <th>Material Fee</th>
                                    <th>Material Fee Wavier</th>
                                    <th>Fee</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
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