@extends('user::layouts.master')
@section('title', 'Admin | Add Student Unit Fee')

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
                <h1>Add Student Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.fee.unit.index', $student->id) }}">Unit Fees</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addstudentfee" action="{{ route('admin.student.fee.unit.store', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ userName('Student', $student->id) }}'s Unit Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="course_id">Intake</label> <span class="required">*</span>
                                <select name="course_id" id="course_id" class="form-control">
                                    <option value="" selected disabled>-- Select Intake Course--</option>
                                    @foreach ($studentIntakeCourses as $intake_course)
                                    <option value="{{ $intake_course->id }}">{{ $intake_course->intakeCourse->course->course_name }} ({{ $intake_course->intakeCourse->intake->name}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="units">Units</label> <span class="required">*</span>
                                <div id="unitsContainer"></div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(function() {
        $('#addstudentfee').validate({
            rules: {
                intake_course_id: {
                    required: true,
                },
                fee_id: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                intake_course_id: "Please choose one intake course",
                fee_id: "Please choose one fee",
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

    document.getElementById('course_id').addEventListener('change', function() {
        const courseId = this.value;
        if (courseId) {
            fetch("{{url('admin/student/unit-fee/course/unit')}}?course_id=" + courseId)
                .then(response => response.json())
                .then(units => {
                    let unitsHtml = '';
                    console.log(units);
                    units.forEach(unit => {
                        unitsHtml += `
                        <div class="form-group">
                            <label>${unit.name}</label>
                            <br>
                    `;
                        unit.fees.forEach(fee => {
                            unitsHtml += `
                                <span style="padding-left: 10px;">
                                    <input type="radio" name="unit_fees[${unit.intake_unit_id}]" value="${fee.id}"> ${fee.fee} (${fee.name})
                                </span>
                                <br>
                        `;
                        });
                        unitsHtml += `
                        </div>
                    `;
                    });
                    document.getElementById('unitsContainer').innerHTML = unitsHtml;
                });
        } else {
            document.getElementById('unitsContainer').innerHTML = '';
        }
    });
</script>
@endsection
