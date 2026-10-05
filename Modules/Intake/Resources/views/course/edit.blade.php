@extends('user::layouts.master')
@section('title', 'Admin | Edit Intake Course')

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
                <h1>Edit Intake Course</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intakeCourse->intake_id) }}">Course</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="editintakecourse" action="{{ route('admin.intake.course.update', $intakeCourse->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ $intakeCourse->course->name }} Course</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="course_id">Course</label> <span class="required">*</span>
                                    <select class="form-control" name="course_id" id="course" disabled>
                                        <option value="{{ $intakeCourse->course_id }}">{{ $intakeCourse->course->course_name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                    <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name" value="{{ $intakeCourse->reference_name }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $intakeCourse->starting_date }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="ending_date">Ending Date</label> <span class="required">*</span>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $intakeCourse->ending_date }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="trainer_id">Teacher</label>
                                    <select class="form-control" name="trainer_id" id="trainer">
                                        <option value="">-- Select Teacher --</option>
                                        @foreach($trainers as $key => $value)
                                        <option value="{{ $value->id }}" @if ($value->id==$intakeCourse->trainer_id) selected @endif>{{ userName('Trainer', $value->id) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="marking_type">Marking Type</label> <span class="required">*</span>
                                    <select name="marking_type" class="form-control" id="marking_type">
                                        <option value="" selected disabled>-- Select Marking Type --</option>
                                        <option @if($intakeCourse->marking_type == 'Subject')selected @endif value="Subject">Subject</option>
                                        <option @if($intakeCourse->marking_type == 'Unit')selected @endif value="Unit">Unit</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($intakeCourse->status == '1')selected @endif value="1">Active</option>
                                        <option @if($intakeCourse->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12"><hr><h6 class="text-muted">Offer Letter Course Details</h6></div>
                                <div class="form-group col-md-3">
                                    <label for="study_mode">Study Mode</label>
                                    <input type="text" name="study_mode" class="form-control" id="study_mode" placeholder="e.g. Face to Face" value="{{ $intakeCourse->study_mode }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="study_location">Study Location</label>
                                    <input type="text" name="study_location" class="form-control" id="study_location" placeholder="Enter Study Location" value="{{ $intakeCourse->study_location }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="work_placement">Work Placement Required</label>
                                    <input type="text" name="work_placement" class="form-control" id="work_placement" placeholder="e.g. No work placement required" value="{{ $intakeCourse->work_placement }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="hours_per_week">Hours Per Week</label>
                                    <input type="text" name="hours_per_week" class="form-control" id="hours_per_week" placeholder="e.g. 20 hours per week" value="{{ $intakeCourse->hours_per_week }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="holiday_breaks">Holiday Breaks</label>
                                    <input type="text" name="holiday_breaks" class="form-control" id="holiday_breaks" placeholder="Enter Holiday Breaks" value="{{ $intakeCourse->holiday_breaks }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="entry_requirements">Entry Requirements</label>
                                    <input type="text" name="entry_requirements" class="form-control" id="entry_requirements" placeholder="Enter Entry Requirements" value="{{ $intakeCourse->entry_requirements }}">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Semesters</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Credits</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($intakeSemesters as $index => $value)
                                <tr>

                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->semester->name }}</td>
                                    <td>{{ $value->semester->credits }}</td>
                                    <td data-sort='{{ convertDate($value->starting_date) }}'>@if ($value->starting_date != NULL) {{ dateFormat($value->starting_date) }} @else - @endif</td>
                                    <td data-sort='{{ convertDate($value->ending_date) }}'>@if ($value->ending_date != NULL) {{ dateFormat($value->ending_date) }} @else - @endif</td>
                                    <td data-sort='{{ convertDate($value->due_date) }}'>@if ($value->due_date != NULL) {{ dateFormat($value->due_date) }} @else - @endif</td>
                                    <td>{{ $value->sequence }}</td>
                                    <td>
                                        @if ($value->status == 1) <span class="status active">Active</span>
                                        @elseif ($value->status == 0) <span class="status inactive">Inactive</span>
                                        @elseif ($value->status == 2) <span class="status deleted">Deleted</span>
                                        @else <span class="status locked">Locked</span>
                                        @endif
                                    </td>
                                    <!-- <td>
                                        <a href="{{ route('admin.intake.semester.edit', $value->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                    </td> -->
                                </tr>
                                <tr>
                                    <td colspan="8">
                                        <h5>Subjects</h5>
                                        <table id="example1" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Credits</th>
                                                <th>Starting Date</th>
                                                <th>Ending Date</th>
                                                <th>Due Date</th>
                                                <th>Sequence</th>
                                                <th>Status</th>
                                            </thead>
                                            @foreach($value->intakeSubject as $key => $sub)
                                            <tbody>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $sub->subject->name }}</td>
                                                <td>{{ $sub->subject->credits }}</td>
                                                <td data-sort='{{ convertDate($sub->starting_date) }}'>@if ($sub->starting_date == NULL) - @else {{ dateFormat($sub->starting_date) }} @endif</td>
                                                <td data-sort='{{ convertDate($sub->ending_date) }}'>@if ($sub->ending_date == NULL) - @else {{ dateFormat($sub->ending_date) }} @endif</td>
                                                <td data-sort='{{ convertDate($sub->due_date) }}'>@if ($sub->due_date == NULL) - @else {{ dateFormat($sub->due_date) }} @endif</td>
                                                <td>{{ $sub->sequence }}</td>
                                                <td>
                                                    @if ($sub->status == 1) <span class="status active">Active</span>
                                                    @elseif ($sub->status == 0) <span class="status inactive">Inactive</span>
                                                    @elseif ($sub->status == 2) <span class="status deleted">Deleted</span>
                                                    @else <span class="status locked">Locked</span>
                                                    @endif
                                                </td>
                                            </tbody>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Sequence</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#editintakecourse').validate({
            rules: {
                course_id: {
                    required: true,
                },
                reference_name: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                ending_date: {
                    required: true,
                },
                marking_type: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                course_id: "Please select one course",
                reference_name: "Please enter reference",
                starting_date: "Please enter starting date",
                ending_date: "Please enter ending date",
                marking_type: "Please select one marking type",
                status: "Please select one status",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection
