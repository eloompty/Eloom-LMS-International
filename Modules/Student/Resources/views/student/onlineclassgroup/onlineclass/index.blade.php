@extends('student::student.layouts.master')
@section('title', 'Student | Group Online Classes')

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
                <h1>{{ $onlineGroup->name }}'s Zoom Classes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.onlineclass.group.index') }}">Student Groups</a></li>
                    <li class="breadcrumb-item active">Group Online Classes</li>
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
                        <h3 class="card-title">List of {{ $onlineGroup->name }}'s Zoom Classes</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($zooms) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Topic</th>
                                    <th>Agenda</th>
                                    <th>Url</th>
                                    <th>Recording</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zooms as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->topic }}</td>
                                    <td>{{ $value->agenda }}</td>
                                    <td>
                                        @if ($value->recording == NULL)
                                        <a href="{{ $value->join_url }}" class="btn btn-info btn-sm" target="_blank"> Join</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->recording != NULL)
                                        <a href="{{ $value->recording }}" class="btn btn-info btn-sm" target="_blank">Play</a>
                                        @endif
                                    </td>
                                    <td>@if ($value->recording != NULL){{ $value->password }} @endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Topic</th>
                                    <th>Agenda</th>
                                    <th>Url</th>
                                    <th>Recording</th>
                                    <th>Password</th>
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