@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Subjects')

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
                <h1>{{ $subjects[0]->intakeSubject->intakeCourse->course->course_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $subjects[0]->intakeSubject->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item active">Subjects</li>
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
                        <h3 class="card-title">List of {{ $subjects[0]->intakeSubject->intakeSemester->semester->name }}'s Subjects</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($subjects) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    @if (getSettingValue('teaching_system') == 'Unit')<th>Units</th>@endif
                                    <th>Time Table</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeSubject->subject->name }}</td>
                                    @if ($value->intakeSubject->starting_date == NULL)
                                    <td>-</td>
                                    @else
                                    <td data-sort='{{ convertDate($value->intakeSubject->starting_date) }}'>{{ dateFormat($value->intakeSubject->starting_date) }}</td>
                                    @endif
                                    @if ($value->intakeSubject->ending_date == NULL)
                                    <td>-</td>
                                    @else
                                    <td data-sort='{{ convertDate($value->intakeSubject->ending_date) }}'>{{ dateFormat($value->intakeSubject->ending_date) }}</td>
                                    @endif
                                    @if ($value->intakeSubject->due_date == NULL)
                                    <td>-</td>
                                    @else
                                    <td data-sort='{{ convertDate($value->intakeSubject->due_date) }}'>{{ dateFormat($value->intakeSubject->due_date) }}</td>
                                    @endif
                                    @if (getSettingValue('teaching_system') == 'Unit')
                                    <td><a href="{{ route('trainer.unit.index', $value->intake_subject_id) }}" class="btn btn-info btn-sm">View</a></td>
                                    @endif
                                    <td><a href="{{ route('trainer.time.index', $value->intake_subject_id) }}" class="btn btn-info btn-sm"> Time Table</a></td>
                                    <td>
                                        @if ($value->intakeSubject->intakeCourse->marking_type == 'Subject')
                                        <a href="{{ route('trainer.subject.mark.index', $value->intake_subject_id) }}" class="btn btn-info btn-sm"> Markings</a>
                                        @endif
                                        <a href="{{ route('trainer.subject.attendance.index', [$value->intake_subject_id, date('Y'), date('m')]) }}" class="btn btn-info btn-sm">Attendance</a>
                                        <a href="{{ route('trainer.subject.edit', $value->intake_subject_id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <a href="{{ route('trainer.subject.resource.index', $value->intake_subject_id) }}" class="btn btn-info btn-sm">Resources</a>
                                        <a href="{{ route('trainer.subject.assignment.index', $value->intake_subject_id) }}" class="btn btn-info btn-sm">Assignments</a>
                                        <a href="{{ route('trainer.subject.chat.index', $value->intake_subject_id) }}" class="btn btn-info btn-sm">Group Chats</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    @if (getSettingValue('teaching_system') == 'Unit')<th>Units</th>@endif
                                    <th>Time Table</th>
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
