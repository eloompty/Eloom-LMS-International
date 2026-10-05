@extends('agentbranchuser::user.layouts.master')
@section('title', 'Agent Branch User | Students')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Students</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('branch-user.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Students</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Students</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('branch-user.student.create') }}" class="btn btn-success">Add Student</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($students) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>DOB</th>
                                    <th>Passport</th>
                                    <th>Citizenship</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ userName('Student', $value->id) }}</td>
                                    <td>{{ $value->student->email }}</td>
                                    <td>
                                        <b>Phone:</b>{{ $value->student->phone }} <br>
                                        <b>Mobile:</b>{{ $value->student->mobile }}
                                    </td>
                                    <td>{{ $value->student->date_of_birth }}</td>
                                    <td>{{ $value->student->passport_no }}</td>
                                    <td>{{ $value->student->citizenship }}</td>
                                    <td>
                                        @if ($value->student->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->student->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('branch-user.student.edit', $value->student->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a href="{{ route('branch-user.student.intake.index', $value->student->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-graduation-cap"></i> Intakes</a>
                                        <a href="{{ route('branch-user.student.offer.index', $value->student->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-envelope"></i> Offers</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>DOB</th>
                                    <th>Passport</th>
                                    <th>Citizenship</th>
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

@section('scripts')
<script>
    $(function() {
        $('#submit').attr('disabled', true);
        $("#status").on('change', function() {
            console.log('option');
            $('#submit').attr('disabled', false);
        });
    });
</script>
@endsection