@extends('user::layouts.master')
@section('title', 'Admin | Student Intake Course Results')

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
                <h1>Student Intake Course Results</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.intake.course.index', $studentIntakeCourse->student_id) }}">Intakes</a></li>
                    <li class="breadcrumb-item active">Results</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        @php
        $competence = $studentIntakeCourse->studentIntakeCourseCompetence;
        if ($competence == NULL) {
        $award_status = $certificate_type = $parchment_issue_date = $parchment_no = NULL;
        } else {
        $award_status = $studentIntakeCourse->studentIntakeCourseCompetence->award_status;
        $certificate_type = $studentIntakeCourse->studentIntakeCourseCompetence->certificate_type;
        $parchment_issue_date = $studentIntakeCourse->studentIntakeCourseCompetence->parchment_issue_date;
        $parchment_no = $studentIntakeCourse->studentIntakeCourseCompetence->parchment_no;
        }
        @endphp
        <form id="competence" @if ($competence==NULL) action="{{ route('admin.student.intake.competence.store', $studentIntakeCourse->id) }}" @else action="{{ route('admin.student.intake.competence.update', $studentIntakeCourse->id) }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Student Intake Course Results</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="award_status">Award Status</label>
                                    <select name="award_status" class="form-control" id="award_status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($award_status=='Y' )selected @endif value="Y">Yes</option>
                                        <option @if($award_status=='N' )selected @endif value="N">No</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="certificate_type">Certificate Type</label>
                                    <input type="text" name="certificate_type" class="form-control" id="certificate_type" placeholder="Enter Certificate Type" value="{{ $certificate_type }}">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="parchment_issue_date">Certificate Issue Date</label>
                                    <input type="date" name="parchment_issue_date" class="form-control" id="parchment_issue_date" placeholder="Enter Certificate Issue Date" value="{{ $parchment_issue_date }}">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="parchment_no">Certificate No</label>
                                    <input type="text" name="parchment_no" class="form-control" id="parchment_no" placeholder="Enter Certificate No" value="{{ $parchment_no }}">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$studentIntakeCourse->intakeCourse->course->course_name}}'s Units</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if($studentIntakeCourse->studentIntakeUnit->count() > 0)
                        <table id="example3" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Unit</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Status</th>
                                    <th>Unit Status</th>
                                    <th>Outcome</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentIntakeCourse->studentIntakeUnit as $index => $value)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $value->intakeUnit->unit->code }}</td>
                                    <td>{{ $value->intakeUnit->unit->name }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date == NULL) - @else {{ dateFormat($value->starting_date) }} @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date == NULL) - @else {{ dateFormat($value->ending_date) }} @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1 && $value->is_complete == 1) <span class="status active">Completed</span>
                                        @elseif ($value->status == 1 && $value->is_complete == 0) <span class="status deleted">Running</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <td>
                                        <?php 
                                        if ($value->outcome == 20 || $value->outcome == 20 || $value->outcome == 60) $class = 'status active';
                                        elseif ($value->outcome == 30 || $value->outcome == 40) $class ='status deleted';
                                        else $class = ''; 
                                        ?>
                                        <span class="{{ $class }}">{{ getIdentifierValue('OUTCOME IDENTIFIER - NATIONAL', $value->outcome) }}</span>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Unit</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Status</th>
                                    <th>Unit Status</th>
                                    <th>Outcome</th>
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
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection