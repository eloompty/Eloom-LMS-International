@extends('user::layouts.master')
@section('title', 'Admin | Add Course')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Course</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($registered==1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
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
        <form id="addcourse" @if ($registered==1) action="{{ route('admin.course.store') }}" @else action="{{ route('admin.unregistered.store') }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Course</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="course_name">Course Name</label> <span class="required">*</span>
                                    <input type="text" name="course_name" class="form-control" id="course_name" placeholder="Enter Course Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                    <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name">
                                </div>
                                @if ($registered == 1)
                                <div class="form-group col-md-4">
                                    <label for="course_code">Course Code</label> <span class="required">*</span>
                                    <input type="text" name="course_code" class="form-control" id="course_code" placeholder="Enter Course Code">
                                </div>
                                @endif
                                <div class="form-group col-md-4">
                                    <label for="details">Course Details</label> <span class="required">*</span>
                                    <textarea class="form-control" rows="3" placeholder="Enter Course Details" name="details"></textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="entry_requirements">Entry Requirements</label> <span class="required">*</span>
                                    <textarea class="form-control" rows="3" placeholder="Enter Entry Requirements" name="entry_requirements"></textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pathways">Pathways</label> <span class="required">*</span>
                                    <textarea class="form-control" rows="3" placeholder="Enter Pathways" name="pathways"></textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="duration">Duration in Years</label> <span class="required">*</span>
                                    <input type="number" name="duration" class="form-control" id="duration" placeholder="Enter Duration in Years">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="study_period">Number of Semester</label> <span class="required">*</span>
                                    <input type="number" name="study_period" class="form-control" id="study_period" placeholder="Enter Number of Semester">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="total_units">Total Subjects</label> <span class="required">*</span>
                                    <input type="number" name="total_units" class="form-control" id="total_units" placeholder="Enter Total Subjects">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fee">Total Fee</label> <span class="required">*</span>
                                    <input type="number" name="fee" class="form-control" id="fee" placeholder="Enter Total Fee" step="0.01">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Course Delivery Site</label> <span class="required">*</span>
                                    <select class="form-control" name="company_delivery_site_id">
                                        <option value="">-- Select Course Delivery Site --</option>
                                        @foreach($delivery_sites as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->site_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12"><hr><h6 class="text-muted">Offer Letter Course Details (defaults)</h6></div>
                                <div class="form-group col-md-4">
                                    <label for="study_mode">Study Mode</label>
                                    <input type="text" name="study_mode" class="form-control" id="study_mode" placeholder="e.g. Face to Face">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="study_location">Study Location</label>
                                    <input type="text" name="study_location" class="form-control" id="study_location" placeholder="Enter Study Location">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="work_placement">Work Placement Required</label>
                                    <input type="text" name="work_placement" class="form-control" id="work_placement" placeholder="e.g. No work placement required">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="hours_per_week">Hours Per Week</label>
                                    <input type="text" name="hours_per_week" class="form-control" id="hours_per_week" placeholder="e.g. 20 hours per week">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="holiday_breaks">Holiday Breaks</label>
                                    <input type="text" name="holiday_breaks" class="form-control" id="holiday_breaks" placeholder="Enter Holiday Breaks">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </form>
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
        $('#addcourse').validate({
            rules: {
                course_name: {
                    required: true,
                },
                details: {
                    required: true,
                },
                course_code: {
                    required: true,
                },
                entry_requirements: {
                    required: true,
                },
                pathways: {
                    required: true,
                },
                reference_name: {
                    required: true,
                },
                delivery_mode: {
                    required: true,
                },
                predominant_delivery_mode: {
                    required: true,
                },
                duration: {
                    required: true,
                },
                study_period: {
                    required: true,
                },
                study_break: {
                    required: true,
                },
                hours: {
                    required: true,
                    digits: true,
                    maxlength: 4
                },
                fee: {
                    required: true,
                },
                onshore_fee: {
                    required: true,
                },
                enrollment_fee: {
                    required: true,
                },
                material_fee: {
                    required: true,
                },
                total_units: {
                    required: true,
                },
                company_delivery_site_id: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                course_name: "Please enter course name",
                details: "Please enter course details",
                course_code: "Please enter course code",
                entry_requirements: "Please enter Entry Requirements",
                pathways: "Please enter Pathways",
                reference_name: "Please enter Reference Name",
                delivery_mode: "Please choose Delivery Mode",
                predominant_delivery_mode: "Please choose Predominant Delivery Mode",
                duration: "Please enter duration",
                study_period: "Please enter study period",
                study_break: "Please enter study break",
                hours: {
                    required: "Please enter hours",
                    maxlength: "Hours cannot be more than 4 digits"
                },
                fee: "Please enter fee",
                onshore_fee: "Please enter onshore fee",
                enrollment_fee: "Please enter enrollment fee",
                material_fee: "Please enter material fee",
                total_units: "Please enter total subjects",
                company_delivery_site_id: "Please choose one delivery site",
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

    var intialVal = $(".initial_fee").val();
    if (intialVal == 0) {
        $(".offshore_initial").hide();
    } else {
        $(".offshore_initial").show();
    }

    function initialFee() {
        if ($('.initial_fee').is(":checked")) {
            $(".offshore_initial").show();
        } else {
            $(".offshore_initial").hide();
        }
    }

    var intialVal = $(".initial_onshore_fee").val();
    if (intialVal == 0) {
        $(".onshore_initial").hide();
    } else {
        $(".onshore_iniital").show();
    }

    function initialOnshoreFee() {
        if ($('.initial_onshore_fee').is(":checked")) {
            $(".onshore_initial").show();
        } else {
            $(".onshore_initial").hide();
        }
    }
</script>
@endsection
