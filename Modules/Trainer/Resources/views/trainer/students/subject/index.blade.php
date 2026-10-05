@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Student Subjects')

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
                <h1>Subjects</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.students.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.students.semester.index', [$student_id, $intake_course_id]) }}">Semesters</a></li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student_id) }}'s subjects</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($subjects) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Credits</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->intakeSubject->subject->name }}</td>
                                    <td>{{ $value->intakeSubject->subject->credits }}</td>
                                    <td>
                                        @if (getSettingValue('teaching_system') == 'Unit')
                                        <a href="{{ route('trainer.students.unit.index', [$student_id, $value->intake_subject_id]) }}" class="btn btn-info btn-sm"> Units</a>
                                        @endif
                                        @if ($value->intakeSubject->intakeCourse->marking_type == 'Subject')
                                        <a href="{{ route('trainer.students.subject.mark.index', $value->id) }}" class="btn btn-info btn-sm"> Markings</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Credits</th>
                                    <th>Actions</th>
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
