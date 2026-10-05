@extends('user::layouts.master')
@section('title', 'Admin | Payment Due Report')

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
                <h1>Payment Due Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Payment Due Report</li>
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
                        <h3 class="card-title">List of Payment Due</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form method="get">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label for="from">From:</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="date" name="from" class="form-control" id="from" value="{{ $from }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label for="to">To:</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="date" name="to" class="form-control" id="to" value="{{ $to }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-2">
                                    <select name="agent" class="form-control" id="agent">
                                        <option value="" selected disabled>-- Select Agent --</option>
                                        @foreach ($agents as $value)
                                        <option @if ($agent == $value->id) selected @endif value="{{ $value->id }}">{{ $value->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-sm-2">
                                    <select name="template_id" class="form-control" id="template_id">
                                        <option value="" selected disabled>-- Select Template --</option>
                                        @foreach (getReportTemplateList() as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.report.due.index') }}">Submit</button>
                                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.report.due.print') }}">Print</button>
                                    <button type="submit" class="btn btn-primary" formaction="{{ route('admin.report.due.show') }}">Show</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        @if(count($dues) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Intake</th>
                                    <th>Course</th>
                                    <!-- <th>Agent</th> -->
                                    <th>Installment Name</th>
                                    <th>Installment Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dues as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $value->student_id) }}</td>
                                    <td>{{ $value->studentIntakeCourseFee->intakeCourse->intake->name }}</td>
                                    <td>{{ $value->studentIntakeCourseFee->intakeCourse->course->course_name }}</td>
                                    <!-- <td>{{ $value->agent }}</td> -->
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->amount }}</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                                    <td>@if ($value->due_date < date('Y-m-d')) Over Due @else Due @endif</td>
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
                                    <th>Fee Installment</th>
                                    <th>Installment Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
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