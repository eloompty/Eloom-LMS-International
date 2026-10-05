@extends('user::layouts.master')
@section('title', 'Admin | Student Details')

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
                <h1>Student Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($student->is_enrolled == 0) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item active">Student Details</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset($student->image) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ username('Student', $student->id) }}</h3>

                        <p class="text-muted text-center">{{ $student->student_id }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Delivery Site</b> <a class="float-right">{{ $student->delivery_site }}</a>
                            </li>
                        </ul>

                        <a href="{{ route('admin.student.edit', $student->id) }}" class="btn btn-info btn-block"><b><i class="fas fa-pencil-alt"></i> Edit</b></a>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ $student->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ $student->phone }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Mobile</strong>
                        <p class="text-muted">{{ $student->mobile }}</p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> {{ $student->primaryAddress->label ?? 'Current' }} Address</strong>
                        <p class="text-muted">{{ formattedAddress($student->primaryAddress) }}</p>

                        @foreach($student->addresses->where('is_primary', false) as $additionalAddress)
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> {{ $additionalAddress->label ?: 'Other' }} Address</strong>
                        <p class="text-muted">{{ formattedAddress($additionalAddress) }}</p>
                        @endforeach

                        <hr>
                        
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#course" data-toggle="tab">Courses</a></li>
                            <li class="nav-item"><a class="nav-link" href="#submissions" data-toggle="tab">Assignment Submissions</a></li>
                            <li class="nav-item"><a class="nav-link" href="#attendances" data-toggle="tab">Attendances</a></li>
                            <li class="nav-item"><a class="nav-link" href="#notes" data-toggle="tab">Notes</a></li>
                            <li class="nav-item"><a class="nav-link" href="#fees" data-toggle="tab">Fees</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="course">
                                @if(count($student->intake) > 0)
                                <table id="example1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Intake</th>
                                            <th>Course</th>
                                            <th>Duration</th>
                                            <th>Starting Date</th>
                                            <th>Ending Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->intake as $index => $value)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $value->intakeCourse->intake->name }}</td>
                                            <td>{{ $value->intakeCourse->course->course_name }}</td>
                                            <td>{{ $value->duration }}</td>
                                            <td data-sort='{{ convertDate($value->starting_date) }}'>{{ dateFormat($value->starting_date) }}</td>
                                            <td data-sort='{{ convertDate($value->ending_date) }}'>{{ dateFormat($value->ending_date) }}</td>
                                            <td>
                                                @if ($value->status == 1) <span class="status active">Active</span>
                                                @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                                @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Intake</th>
                                            <th>Course</th>
                                            <th>Duration</th>
                                            <th>Starting Date</th>
                                            <th>Ending Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="submissions">
                                @if(count($student->assignmentSubmission) > 0)
                                <table id="example5" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Assignment</th>
                                            <th>Submitted Date</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                            <th>Credits</th>
                                            <th>Graded Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->assignmentSubmission as $index => $value)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $value->assignment->name }}</td>
                                            <td>
                                                @if ($value->assignment->type == 'file')
                                                <a href="{{ asset($value->path) }}" target="_blank"><img src="{{ asset(filePath($value->path)) }}" alt="" width="48" /></a>
                                                @elseif ($value->assignment->type == 'mcq')
                                                <a href="{{ route('admin.student.intake.unit.submission.mcq', $value->id) }}" target="_blank"><img src="{{ asset('files/mcq.png') }}" alt="" width="48" /></a>
                                                @else
                                                <a href="{{ route('admin.student.intake.unit.submission.question', $value->id) }}" target="_blank"><img src="{{ asset('files/qa.png') }}" alt="" width="48" /></a>
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
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Assignment</th>
                                            <th>Submitted Date</th>
                                            <th>Grade</th>
                                            <th>Remarks</th>
                                            <th>Credits</th>
                                            <th>Graded Date</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="attendances">
                                @if(count($student->attendances) > 0)
                                <table id="example6" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->attendances as $index => $value)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td data-sort='{{ convertDate($value->date) }}'>{{ dateFormat($value->date) }}
                                            <td>{{ $value->intakeUnit->unit->code }} {{ $value->intakeUnit->unit->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Unit</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="notes">
                                <div class="col-md-12 text-right"><a href="{{ route('admin.student.note.create', $student->id) }}" class="btn btn-success">Add Note</a></div>
                                @if(count($student->notes) > 0)
                                <table id="example2" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Notes</th>
                                            <th>Added By</th>
                                            <th>Added At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->notes as $index => $value)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $value->notes }}</td>
                                            <td>{{ userName('Admin', $value->user_id) }}</td>
                                            <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}
                                            <td>
                                                <a href="{{ route('admin.student.note.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                @if ($value->status != 2)
                                                <a href="{{ route('admin.student.note.delete', $value->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Notes</th>
                                            <th>Added By</th>
                                            <th>Added At</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="fees">
                                @if(count($fees) > 0)
                                @foreach($fees as $index => $value)
                                <div class="row">
                                    <div class="col-md-2" style="text-align: center;">
                                        <label>{{ $value->intakeCourse->intake->name }}({{ $value->intakeCourse->course->course_name }})</label>
                                    </div>
                                    <div class="col-md-2" style="text-align: center;">
                                        <label>Name: {{ $value->name }}</label>
                                    </div>
                                    <div class="col-md-2" style="text-align: center;">
                                        <label>Total Fee: {{ $value->fee + $value->enrollment_fee + $value->material_fee }}</label>
                                    </div>
                                    <div class="col-md-2" style="text-align: center;">
                                        <label>Paid: {{ $value->paid }}</label>
                                    </div>
                                    <div class="col-md-2" style="text-align: center;">
                                        <label>Remaining: {{ $value->remaining }}</label>
                                    </div>
                                </div>
                                @foreach($value->installments as $key => $installment)
                                <div class="row">
                                    <div class="col-12" id="accordion">
                                        <div class="card card-primary card-outline">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapse{{ $key }}">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        {{ $key + 1 }}. {{ $installment->name }}
                                                    </h4>
                                                    <div class="text-right">{{ studentPaymentStatus($installment->status) }}</div>
                                                </div>
                                            </a>
                                            <div id="collapse{{ $key }}" class="collapse" data-parent="#accordion">
                                                <div class="card-body">
                                                    <b>Installment Fee: </b>{{ $installment->amount }} <br>
                                                    <b>Due Date: </b> {{ dateFormat($value->due_date) }}
                                                    @if ($installment->status == 2)
                                                    <br> <b>Paid Amount: </b> {{ $installment->studentIntakeCourseFeePayment->paid_amount }}
                                                    <br> <b>Paid Date: </b> {{ dateFormat($installment->studentIntakeCourseFeePayment->paid_date) }}
                                                    <br> <a href="{{ asset($installment->studentIntakeCourseFeePayment->receipt) }}" target="_blank"><img src="{{ asset($installment->studentIntakeCourseFeePayment->receipt) }}" alt="" width="200" /></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                @endforeach
                                @endforeach
                                @else
                                <h3>No Data Found</h3>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function() {
        $('#example3').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#example4').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#example5').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
        $('#example6').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endsection