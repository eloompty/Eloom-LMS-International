@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Assignments')

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
                <h1>Assignments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Assignments</li>
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
                        <h3 class="card-title">List of assignments</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($assignments) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th>Due Date</th>
                                    <th>Uploaded By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->intakeSubject->intakeCourse->course->course_name }}</td>
                                    <td>{{ $value->intakeSubject->subject->code }} {{ $value->intakeSubject->subject->name }}</td>
                                    <td>{{ strtoupper($value->type) }}</td>
                                    <td>
                                        @if ($value->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'multiple files')
                                        <a href="{{ route('trainer.subject.assignment.files.edit', $value->id) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->type == 'mcq')
                                        <a href="{{ route('trainer.subject.assignment.mcq.show', $value->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a href="{{ route('trainer.subject.assignment.question.show', $value->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                                    <td>
                                        @if ($value->uploaded_by == 'Admin')Admin
                                        @elseif($value->uploaded_by == 'Trainer'){{ userName('Trainer', $value->uploaded_user_id) }} (Trainer)
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->type == 'file')
                                        <a href="{{ route('trainer.subject.assignment.edit', [$value->id, $value->trainer_intake_id]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @elseif ($value->type == 'multiple files')
                                        <a href="{{ route('trainer.subject.assignment.files.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @elseif ($value->type == 'mcq')
                                        <a href="{{ route('trainer.subject.assignment.mcq.show', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Show</a>
                                        @else
                                        <a href="{{ route('trainer.subject.assignment.question.show', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Show</a>
                                        @endif
                                        <a href="{{ route('trainer.subject.submission.index', [$value->id, $value->trainer_intake_id]) }}" class="btn btn-info btn-sm"> Submissions</a>
                                        <a href="{{ route('trainer.subject.resubmission.index', [$value->id, $value->trainer_intake_id]) }}" class="btn btn-info btn-sm"> Resubmissions Request</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th>Due Date</th>
                                    <th>Uploaded By</th>
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