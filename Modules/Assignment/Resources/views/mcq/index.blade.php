@extends('user::layouts.master')
@section('title', 'Admin | MCQ Assignment')

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
                <h1>MCQ Assignment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.assignment.index') }}">Assignments</a></li>
                    <li class="breadcrumb-item active">MCQ Assignment</li>
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
                        <h3 class="card-title">List of {{ $assignment->name }}'s MCQs</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.intake.unit.assignment.mcq.create', $assignment->id) }}" class="btn btn-success">Add MCQ</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($questions) > 0)
                        <div class="card-body">
                            @foreach($questions as $question)
                            <div class="card mb-3">
                                <div class="card-header mcq-question">
                                    <div class="row">
                                        <div class="col-sm-10">{{ $no++ }}. {{ $question->question }}</div>
                                        <div class="col-sm-2 text-right"><a href="{{ route('admin.intake.unit.assignment.mcq.edit', $question->id) }}" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</a></div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    @foreach($question->choices as $option)
                                    <div class="form-check">
                                        <label class="form-check-label" for="option-{{ $option->id }}">
                                            @if ($option->is_correct == 1)<span class="option right"><i class="fas fa-check-circle">@else<span class="option wrong"><i class="fas fa-circle-notch">@endif</i> {{ $option->choice }} </span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
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