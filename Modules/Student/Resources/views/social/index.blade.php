@extends('user::layouts.master')
@section('title', 'Admin | Student Socials')

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
                <h1>Student Socials</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Socials</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }}'s Socials</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.social.create', $student->id) }}" class="btn btn-success">Add Student Social</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($socials) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Category</th>
                                    <th>Social Link</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($socials as $index => $value)
                                <tr>
                                    <td><a href="{{ asset($value->social_category->image) }}" target="_blank"><img src="{{  asset($value->social_category->image) }}" alt="" width="48" /></a></td>
                                    <td>{{ $value->social_category->name }}</td>
                                    <td>{{ $value->social_link }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.student.social.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.student.social.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Icon</th>
                                    <th>Category</th>
                                    <th>Social Link</th>
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