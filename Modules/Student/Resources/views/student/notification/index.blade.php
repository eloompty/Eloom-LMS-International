@extends('student::student.layouts.master')
@section('title', 'Student | Notifications')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Notifications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
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
                        <h3 class="card-title">List of Notifications</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if($notifications->count() > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Body</th>
                                    <th>Link</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($notifications as $index => $value)
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->body }}</td>
                                    <td>
                                        @if ($value->type == 'Assignment')
                                        <a href="{{ $value->assignment_url }}" class="btn btn-info btn-sm"> View</a>
                                        @elseif ($value->type == 'OnlineClass')
                                        @php $url = 'https://us06web.zoom.us/j/' . $value->link @endphp
                                        <a href="{{ $url }}" class="btn btn-info btn-sm" target="_blank"> Join</a>
                                        @elseif ($value->type == 'Resubmission')
                                        @php $link = explode(",", $value->link) @endphp
                                        <a href="{{ route('student.submission.index', $link) }}" class="btn btn-info btn-sm"> View</a>
                                        @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Body</th>
                                    <th>Link</th>
                                    <th>Date</th>
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