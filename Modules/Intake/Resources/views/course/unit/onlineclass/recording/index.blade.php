@extends('user::layouts.master')
@section('title', 'Admin | Intake Unit Zoom Recording')

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
                <h1>Zoom Recording</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $recording->onlineClass->intakeUnit->intakeCourse->intake_id) }}">Intake Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.index', $recording->onlineClass->intakeUnit->id) }}">Intake Units</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.unit.onlineclass.index', $recording->onlineClass->intakeUnit->id) }}">Intake Unit Zoom Classes</a></li>
                    <li class="breadcrumb-item active">Intake Unit Zoom Recording</li>
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
                        <h3 class="card-title">List of {{ $recording->onlineClass->topic }}'s Zoom Recordings</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Share URL</th>
                                    <th>File Type</th>
                                    <th>File Size</th>
                                    <th>Play URL</th>
                                    <th>Download URL</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="{{ $recording->share_url }}" class="btn btn-info btn-sm" target="_blank"> Share</a></td>
                                    <td>{{ $recording->file_type }}</td>
                                    <td>{{ $recording->file_size }}</td>
                                    <td><a href="{{ $recording->play_url }}" class="btn btn-info btn-sm" target="_blank"> Play</a></td>
                                    <td><a href="{{ $recording->download_url }}" class="btn btn-info btn-sm" target="_blank"> Download</a></td>
                                    <td>{{ $recording->password }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Share URL</th>
                                    <th>File Type</th>
                                    <th>File Size</th>
                                    <th>Play URL</th>
                                    <th>Download URL</th>
                                    <th>Password</th>
                                </tr>
                            </tfoot>
                        </table>
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