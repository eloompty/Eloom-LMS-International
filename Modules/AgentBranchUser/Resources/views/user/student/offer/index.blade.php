@extends('agentbranchuser::user.layouts.master')
@section('title', 'Agent Branch User | Student Offers')

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
                <h1>Student Offers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch-user.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('branch-user.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Offers</li>
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
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }} Offers</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('branch-user.student.offer.create', $student->id) }}" class="btn btn-success">Add Offer</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($letters) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Generated Date</th>
                                    <th>Intake Courses</th>
                                    <th>Condition</th>
                                    <th>Credit</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($letters as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>{{ $value->intakes }}</td>
                                    <td>{{ $value->condition_title }}</td>
                                    <td>{{ $value->credit_title }}</td>
                                    <td>@if ($value->expiry_date != NULL) {{ dateFormat($value->expiry_date) }} @else - @endif</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('branch-user.student.offer.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a href="{{ route('branch-user.student.offer.print', $value->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-file-pdf"></i> Print</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Generated Date</th>
                                    <th>Intake Courses</th>
                                    <th>Condition</th>
                                    <th>Credit</th>
                                    <th>Expiry Date</th>
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