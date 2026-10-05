@extends('user::layouts.master')
@section('title', 'Admin | Assignment Submissions')

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
                <h1>Assignment Submissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Submissions</li>
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
                        <h3 class="card-title">List of assignment submissions</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('admin.submission.index') }}" method="get" style="float:right;">
                            <div class="input-group">
                                <input type="search" name="search" class="form-control" id="search" placeholder="Search" value="{{ $search }}">
                                <span class="input-group-prepend">
                                    <button tyep="submit" class="btn btn-primary"><i class="fas fa-search"></i> </button>
                                </span>
                            </div>
                        </form>
                        @if(count($submissions) > 0)
                        <table id="" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Student</th>
                                    <th>Assignment</th>
                                    <th>Submitted Date</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Graded Date</th>
                                    <th>Graded File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $index => $value)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $value->assignment->name }}</td>
                                    <td>{{ userName('Student', $value->student_id) }}</td>
                                    <td>
                                        @if ($value->assignment->type == 'file')
                                        <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                        @elseif ($value->assignment->type == 'multiple files')
                                        <a href="{{ route('admin.submission.files', $value->id) }}" target="_blank"><img src="{{ asset('files/multiple_file.png') }}" alt="" width="48" /></a>
                                        @elseif ($value->assignment->type == 'mcq')
                                        <a href="{{ route('admin.submission.mcq', $value->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                        @else
                                        <a href="{{ route('admin.submission.question', $value->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>@if ($value->assignment_grade_id == NULL) Not graded @else {{ $value->assignmentGrade->name }} @endif</td>
                                    <td>{{ $value->remarks }}</td>
                                    <td>{{ $value->credits }}</td>
                                    @if ($value->graded_date == NULL)
                                    <td> -
                                        @else
                                    <td data-sort='{{ convertDate($value->graded_date) }}'>{{ dateFormat($value->graded_date) }}
                                        @endif
                                    <td>
                                        @if ($value->assignmentSubmissionGrade == NULL) -
                                        @else <a href="{{ asset($value->assignmentSubmissionGrade->path) }}" target="_blank"><img src="{{ asset(filePath($value->assignmentSubmissionGrade->path)) }}" alt="" width="48" /></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (filePath($value->path) == 'files/pdf.png')
                                        <a href="{{ route('admin.submission.pdf', [$value->id, 'student']) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @elseif ($value->assignment->type == 'multiple files')
                                        <a href="{{ route('admin.submission.files', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @else
                                        <a href="{{ route('admin.submission.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Student</th>
                                    <th>Assignment</th>
                                    <th>Submitted Date</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Credits</th>
                                    <th>Graded Date</th>
                                    <th>Graded File</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        <br>
                        <div class="row col-sm-12 custom_pagination">
                            <div class="col-sm-3">
                                Showing {{ $submissions->firstItem() }} to {{ $submissions->lastItem() }} of total {{$submissions->total()}} entries
                            </div>
                            <div class="col-sm-9">
                                <div class="float-right">
                                    {{ $submissions->links() }}
                                </div>
                            </div>
                        </div>
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