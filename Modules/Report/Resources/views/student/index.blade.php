@extends('user::layouts.master')
@section('title', 'Admin | Students Report')

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
                <h1>Students Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Students Report</li>
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
                        <h3 class="card-title">List of Students</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form method="get">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label for="from">From:</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="date" name="from" class="form-control" id="from" value="{{ $from }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label for="to">To:</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="date" name="to" class="form-control" id="to" value="{{ $to }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <select name="template_id" class="form-control" id="template_id">
                                        <option value="" selected disabled>-- Select Template --</option>
                                        @foreach (getReportTemplateList() as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.report.student.index') }}">Submit</button>
                                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.report.student.print') }}">Print</button>
                                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.report.student.show') }}">Show</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        @if(count($students) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <!-- <th>Agent</th> -->
                                    <th>Fee Name</th>
                                    <th>Fee Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $value->student_id) }}</td>
                                    <td>{{ $value->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->intakeCourse->course->course_name }}</td>
                                    <!-- <td>@if ($value->student->studentAgent == NULL) - @else {{ $value->student->studentAgent->agent->company_name }} @endif</td> -->
                                    <td>{{ $value->fee_name }}</td>
                                    <td>{{ $value->fee }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <!-- <th>Agent</th> -->
                                    <th>Fee Name</th>
                                    <th>Fee Amount</th>
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