@extends('user::layouts.master')
@section('title', 'Admin | Students')

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
                <h1>Students</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Students</li>
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
                        <h3 class="card-title">List of Students</h3>
                        <div class="col-md-12 text-right"><a @if ($table=='offer' ) href="{{ route('admin.student.offer.create') }}" @else href="{{ route('admin.student.create') }}" @endif class="btn btn-success">Add Student</a></div>
                        <div class="col-md-12">
                            @if($status == NULL)<a @if ($table=='offer' ) href="{{ url('admin/offer?status=deleted') }}" @else href="{{ url('admin/student?status=deleted') }}" @endif class="btn btn-danger">Show Deleted Students</a>
                            @elseif ($status == 'deleted')
                            <a @if ($table=='offer' ) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif class="btn btn-success">Show Active Students</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($students) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>DOB</th>
                                    <th>Phone</th>
                                    @if ($table=='offer')<th>Student Status</th> @endif
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>@if ($value->id_no == NULL) - @else {{ $value->id_no }} @endif</td>
                                    <td><a href="{{ route('admin.student.show', $value->id) }}">{{ userName('Student', $value->id) }}</a></td>
                                    <td data-sort='{{ convertDate($value->date_of_birth) }}'>{{ dateFormat($value->date_of_birth) }}</td>
                                    <td>{{ $value->mobile }}</td>
                                    @if ($table=='offer')
                                    <td>
                                        <form action="{{ route('admin.student.offer.update.status', $value->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="status">Select Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="Enquired" @if ($value->student_status == "Enquired") selected @endif>Enquired</option>
                                                    <option value="Contacted" @if ($value->student_status == "Contacted") selected @endif>Contacted</option>
                                                    <option value="Converted" @if ($value->student_status == "Converted") selected @endif>Converted</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </td>
                                    @endif
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @else <span class="status deleted">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a @if ($value->is_enrolled == 1) href="{{ route('admin.student.intake.course.index', $value->id) }}" @else href="{{ route('admin.student.intake.course.offer.index', $value->id) }}" @endif class="btn btn-warning btn-sm" title="{{ userName('Student', $value->id) }}'s Intakes"><i class="fas fa-graduation-cap"></i> Intakes</a>
                                        @if ($table =='students')
                                        @if (feeSetting('fee_module') == 'yes')
                                        <a href="{{ route('admin.student.fee.index', $value->id) }}" class="btn btn-success btn-sm" title="{{ userName('Student', $value->id) }}'s Fees"><i class="fas fa-money-bill"></i> Fees</a>
                                        @endif
                                        @if (feeSetting('unit_wise_fee') == 'yes')
                                        <a href="{{ route('admin.student.fee.unit.index', $value->id) }}" class="btn btn-success btn-sm" title="{{ userName('Student', $value->id) }}'s unit Fees"><i class="fas fa-money-bill"></i> Unit Fees</a>
                                        @endif
                                        @if (feeSetting('payment_module') == 'yes')
                                        <a href="{{ route('admin.student.payment.index', $value->id) }}" class="btn btn-secondary btn-sm" title="{{ userName('Student', $value->id) }}'s Payments"><i class="fas fa-money-bill"></i> Payments</a>
                                        @endif
                                        @endif
                                        <a @if ($value->is_enrolled == 1) href="{{ route('admin.student.edit', $value->id) }}" @else href="{{ route('admin.student.offer.edit', $value->id) }}" @endif class="btn btn-info btn-sm" title="Edit {{ userName('Student', $value->id) }}'s Information"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        @if ($table =='students')
                                        <a href="{{ route('admin.student.offer.letter.index', $value->id) }}" class="btn btn-primary btn-sm" title="{{ userName('Student', $value->id) }}'s Offers"><i class="fas fa-envelope"></i> Offers</a>
                                        <a href="{{ route('admin.student.template.index', $value->id) }}" class="btn btn-secondary btn-sm" title="{{ userName('Student', $value->id) }}'s Template"><i class="fas fa-book"></i> Templates</a>
                                        @endif
                                        @if ($value->status != 2)
                                        <a href="{{ route('admin.student.delete', $value->id) }}" class="btn btn-danger btn-sm" title="Delete {{ userName('Student', $value->id) }}"><i class="fas fa-trash"></i> Delete</a>
                                        @endif
                                        <a href="{{ route('admin.student.email.index', $value->id) }}" class="btn btn-dark btn-sm" title="{{ userName('Student', $value->id) }}'s Email"><i class="fas fa-envelope-open"></i> Emails</a>
                                        @if ($table =='students')
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                                More
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <!-- <li style="margin:5pt"><a href="{{ route('admin.student.intake.course.fee.index', $value->id) }}" style="width: 100%;" class="btn btn-warning btn-sm" title="{{ userName('Student', $value->id) }}'s Fees"><i class="fas fa-money-bill"></i> Fees</a></li> -->
                                                <li style="margin:5pt"><a href="{{ route('admin.student.dashboard', $value->id) }}" target="_blank" style="width: 100%;" class="btn btn-info btn-sm" title="{{ userName('Student', $value->id) }}'s Dashboard"><i class="fas fa-eye"></i> Dashboard</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.student.social.index', $value->id) }}" style="width: 100%;" class="btn btn-primary btn-sm" title="{{ userName('Student', $value->id) }}'s Socials"><i class="fas fa-people-arrows"></i> Socials</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.student.document.index', $value->id) }}" style="width: 100%;" class="btn btn-primary btn-sm" title="{{ userName('Student', $value->id) }}'s Document"><i class="fas fa-file"></i> Document</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.student.log.index', $value->id) }}" style="width: 100%;" class="btn btn-dark btn-sm" title="{{ userName('Student', $value->id) }}'s Logs"><i class="fas fa-chart-line"></i> Logs</a></li>
                                                <li style="margin:5pt"><a href="{{ route('admin.student.device.index', $value->id) }}" style="width: 100%;" class="btn btn-success btn-sm" title="{{ userName('Student', $value->id) }}'s Device"><i class="fas fa-mobile"></i> Devices</a></li>
                                            </ul>
                                        </div>
                                        @else
                                        <!-- <a href="{{ route('admin.student.offer.enroll', $value->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-user"></i> Enroll</a> -->
                                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modal-default{{$value->id}}">
                                            <i class="fas fa-user"></i> Enroll
                                        </button>
                                        <div class="modal fade" id="modal-default{{$value->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Enroll Course</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.student.offer.enroll', $value->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="intake_course_id">Course</label>
                                                                        <select class="form-control" name="intake_course_id" id="intake_course_id" required>
                                                                            <option value="">-- Select Course --</option>
                                                                            @foreach($value->intake->where('is_enrolled', 0) as $intake)
                                                                            <option value="{{ $intake->intake_course_id }}"> {{ $intake->intakeCourse->course->course_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="file">Upload File</label>
                                                                        <input type="file" name="file" class="form-control" id="file" placeholder="Choose File">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="remarks">Add Remarks</label>
                                                                        <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Enroll</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>DOB</th>
                                    <th>Phone</th>
                                    @if ($table=='offer' )<th>Student Status</th> @endif
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