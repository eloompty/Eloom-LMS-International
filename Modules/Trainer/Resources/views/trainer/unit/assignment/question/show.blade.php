@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Question Assignment Details')

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
                <h1>{{ $trainerIntake->intakeUnit->unit->name }}'s Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.course.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.semester.index', $trainerIntake->intake_course_id) }}">Semesters</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.subject.index', $trainerIntake->intake_semester_id) }}">Subjects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.unit.index', $trainerIntake->intake_subject_id) }}">Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.assignment.index', $trainerIntake->id) }}">Assignments</a></li>
                    <li class="breadcrumb-item active">Question Assignment</li>
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
                        <h3 class="card-title">List of {{ $assignment->name }}'s Questions</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('trainer.assignment.question.create', $assignment->id) }}" class="btn btn-success">Add Question</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($questions) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <!-- <td>{{ $value->question }}</td> -->
                                    <td>
                                        <form id="" action="{{ route('trainer.assignment.question.update', $value->id) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="question" placeholder="Enter Question" value="{{ $value->question }}" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <select name="status" class="form-control" id="status" required>
                                                        <option value="" selected disabled>-- Select Status --</option>
                                                        <option @if($value->status == '1')selected @endif value="1">Active</option>
                                                        <option @if($value->status == '0')selected @endif value="0">Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
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