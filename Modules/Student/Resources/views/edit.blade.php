@extends('user::layouts.master')
@section('title', 'Admin | Edit Student')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($student->is_enrolled == 0) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"> Edit Student</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- <div class="container"> -->
                        <ul class="nav nav-tabs mb-3" id="tabNavigation">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#personal">Personal Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#contact">Address</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#agent">Agent</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#other">Others</a>
                            </li>
                        </ul>
                        <form id="multiStepForm" action="{{ route('admin.student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="formTabs">
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <h3>Personal Information </h3>
                                    <input type="hidden" name="url" value="{{ url()->previous() }}">
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="salutation">Salutation</label> <span class="required">*</span>
                                            <select name="salutation" class="form-control" id="salutation">
                                                <option value="" selected disabled>-- Select Salutation --</option>
                                                <option @if($student->salutation == 'Mr.')selected @endif value="Mr.">Mr.</option>
                                                <option @if($student->salutation == 'Ms.')selected @endif value="Ms.">Ms.</option>
                                                <option @if($student->salutation == 'Miss')selected @endif value="Miss">Miss</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="first_name">First Name</label> <span class="required">*</span>
                                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name" value="{{ $student->first_name }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="family_name">Family Name</label> <span class="required">*</span>
                                            <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name" value="{{ $student->family_name }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="date_of_birth">Date of Birth</label> <span class="required">*</span>
                                            <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth" value="{{ $student->date_of_birth }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone" value="{{ $student->phone }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="mobile">Mobile</label> <span class="required">*</span>
                                            <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile" value="{{ $student->mobile }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="citizenship_country">Citizenship Country</label> <span class="required">*</span>
                                            <select class="form-control" name="citizenship_country" id="citizenship_country">
                                                <option value="">-- Select Citizenship Country --</option>
                                                @foreach($countries as $key => $value)
                                                <option value="{{ $key }}" @if ($key==$student->citizenship_country) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="email">Email</label> <span class="required">*</span>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="{{ $student->email }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="email_alternative">Email Alternative</label>
                                            <input type="text" name="email_alternative" class="form-control" id="email_alternative" placeholder="Enter Email Alternative" value="{{ $student->email_alternative }}">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">-- Select Gender --</option>
                                                <option @if($student->gender == 'Male') selected @endif value="Male">Male</option>
                                                <option @if($student->gender == 'Female') selected @endif value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="id_no">Student ID</label>
                                            <input type="text" name="id_no" class="form-control" id="id_no" placeholder="Enter Student Id" value="{{ $student_id_value ?? $student->id_no }}" @if($student_id_readonly ?? false) readonly @endif>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="status">Delivery Site</label> <span class="required">*</span>
                                            <select class="form-control" name="company_delivery_site_id">
                                                <option value="">-- Select Delivery Site --</option>
                                                @foreach($delivery_sites as $key => $value)
                                                <option value="{{ $value->id }}" @if ($value->id==$site_id) selected @endif>{{ $value->site_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="allow_submission_after_due_date">Allow Assignment Submission After Due Date</label>
                                            <select name="allow_submission_after_due_date" class="form-control" id="allow_submission_after_due_date">
                                                <option value="" selected disabled>-- Select Allow Assignment Submission After Due Date --</option>
                                                <option @if($student->allow_submission_after_due_date == 'on') selected @endif value="on">On</option>
                                                <option @if($student->allow_submission_after_due_date == 'off') selected @endif value="off">Off</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="image">Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);" value="{{ $student->image }}">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <img src="{{ asset($student->image) }}" id="box-image" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel">
                                    <h3>Current Address Information</h3>
                                    <div class="row">
                                        @include('partials.address-fields', ['address' => $student->address, 'addressIdPrefix' => 'student'])
                                    </div>
                                    @include('partials.student-additional-addresses', ['student' => $student])
                                    <hr>
                                    <div id="dynamicTable">
                                        @foreach($student->guardians as $relation)
                                        <h5>{{ $relation->relation }} Contact Information</h5>
                                        <div class="row mb-2">
                                            <input type="hidden" name="relation[]" class="form-control" placeholder="Relation" value="{{ $relation->relation }}">
                                            <div class="col">
                                                <input type="text" name="name[]" class="form-control" placeholder="Name" value="{{ $relation->name }}">
                                            </div>
                                            <div class="col">
                                                <input type="text" name="contact_no[]" class="form-control" placeholder="Contact No" value="{{ $relation->contact_no }}">
                                            </div>
                                            <div class="col">
                                                <input type="info_email" name="info_email[]" class="form-control" placeholder="Email" value="{{ $relation->email }}">
                                            </div>
                                            <div class="col">
                                                <input type="text" name="occupation[]" class="form-control" placeholder="Occupation" value="{{ $relation->occupation }}">
                                            </div>
                                            <div class="col">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <!-- <div class="tab-pane fade" id="agent" role="tabpanel">
                                    <h3>Agent</h3>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="agent_id">Agent</label>
                                                <select id="agent_id" name="agent_id" class="form-control">
                                                    <option value="" selected disabled>-- Select Agent --</option>
                                                    @foreach($agents as $key => $value)
                                                    <option value="{{ $key }}" @if ($key==$agent_id) selected @endif> {{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="branch_id">Branch</label>
                                                <select name="branch_id" id="branch_id" class="form-control">
                                                    <option value="" selected disabled>-- Select Branch --</option>
                                                    @foreach($branches as $key => $value)
                                                    <option value="{{ $key }}" @if ($key==$branch_id) selected @endif> {{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="user_id">User</label>
                                                <select name="user_id" id="user_id" class="form-control">
                                                    <option value="" selected disabled>-- Select User --</option>
                                                    @foreach($users as $key => $value)
                                                    <option value="{{ $key }}" @if ($key==$user_id) selected @endif> {{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div> -->
                                <div class="tab-pane fade" id="other" role="tabpanel">
                                    <div class="form-group">
                                        <label for="status">Status</label> <span class="required">*</span>
                                        <select name="status" class="form-control" id="status">
                                            <option value="" selected disabled>-- Select Status --</option>
                                            <option @if($student->status == '1')selected @endif value="1">Active</option>
                                            <option @if($student->status == '0')selected @endif value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="submit" class="btn btn-primary submit">Submit</button>
                                </div>
                            </div>
                        </form>
                        <!-- </div> -->
                    </div>
                    <!-- /.card-body -->
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    $('#location_id').change(function() {
        var locationID = $(this).val();
        if (locationID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/sub_location')}}?location_id=" + locationID,
                success: function(res) {
                    if (res) {
                        $("#location_level_2").empty();
                        $("#location_level_2").append('<option value="">-- Select District --</option>');
                        $.each(res, function(key, value) {
                            $("#location_level_2").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#location_level_2").empty();
                    }
                }
            });
        } else {
            $("#location_level_2").empty();
        }
    });

    $('#location_level_2').change(function() {
        var locationID = $(this).val();
        if (locationID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/sub_location')}}?location_id=" + locationID,
                success: function(res) {
                    if (res) {
                        $("#location_level_3").empty();
                        $("#location_level_3").append('<option value="">-- Select Local Body --</option>');
                        $.each(res, function(key, value) {
                            $("#location_level_3").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#location_level_3").empty();
                    }
                }
            });
        } else {
            $("#location_level_3").empty();
        }
    });

    $('#location_level_3').change(function() {
        var locationID = $(this).val();
        if (locationID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/sub_location')}}?location_id=" + locationID,
                success: function(res) {
                    if (res) {
                        $("#location_level_4").empty();
                        $("#location_level_4").append('<option value="">-- Select Ward --</option>');
                        $.each(res, function(key, value) {
                            $("#location_level_4").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#location_level_4").empty();
                    }
                }
            });
        } else {
            $("#location_level_4").empty();
        }
    });

    $('#location_level_4').change(function() {
        var locationID = $(this).val();
        if (locationID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/sub_location')}}?location_id=" + locationID,
                success: function(res) {
                    if (res) {
                        $("#location_level_5").empty();
                        $("#location_level_5").append('<option value="">-- Select Tole --</option>');
                        $.each(res, function(key, value) {
                            $("#location_level_5").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#location_level_5").empty();
                    }
                }
            });
        } else {
            $("#location_level_5").empty();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addRow').addEventListener('click', function() {
            addRow();
        });

        document.getElementById('dynamicTable').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeRow')) {
                removeRow(e.target);
            }
        });
    });

    function addRow() {
        let newRow = `
        <div class="row mb-2">
            <div class="col">
                <input type="text" name="name[]" class="form-control" placeholder="Name">
            </div>
            <div class="col">
                <input type="text" name="contact_no[]" class="form-control" placeholder="Contact No">
            </div>
            <div class="col">
                <input type="text" name="occupation[]" class="form-control" placeholder="Occupation">
            </div>
            <div class="col">
                <input type="text" name="relation[]" class="form-control" placeholder="Relation">
            </div>
            <div class="col">
                <button type="button" class="btn btn-success addRow">+</button>
                <button type="button" class="btn btn-danger removeRow">-</button>
            </div>
        </div>
    `;
        document.getElementById('dynamicTable').insertAdjacentHTML('beforeend', newRow);
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('#dynamicTable .row');
        if (rows.length > 1) {
            button.closest('.row').remove();
        } else {
            alert('At least one row is required.');
        }
    }


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(80)
                    .height(80);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#agent_id').change(function() {
        var agentID = $(this).val();
        if (agentID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/branch')}}?agent_id=" + agentID,
                success: function(res) {
                    if (res) {
                        $("#branch_id").empty();
                        $("#branch_id").append('<option value="">-- Select Branch --</option>');
                        $.each(res, function(key, value) {
                            $("#branch_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#branch_id").empty();
                    }
                }
            });
        } else {
            $("#branch_id").empty();
        }
    });

    $('#branch_id').change(function() {
        var branchID = $(this).val();
        if (branchID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/student/branch/user')}}?branch_id=" + branchID,
                success: function(res) {
                    if (res) {
                        $("#user_id").empty();
                        $("#user_id").append('<option value="">-- Select User --</option>');
                        $.each(res, function(key, value) {
                            $("#user_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#user_id").empty();
                    }
                }
            });
        } else {
            $("#user_id").empty();
        }
    });


    $(document).ready(function() {
        // Tab navigation
        $('#tabNavigation a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Validation rules for tab 1
        var tab1ValidationRules = {
            salutation: {
                required: true,
            },
            first_name: {
                required: true,
            },
            family_name: {
                required: true,
            },
            date_of_birth: {
                required: true,
            },
            citizenship_country: {
                required: true,
            },
            phone: {
                digits: true,
                minlength: 7
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 7
            },
            phone_work: {
                digits: true,
                minlength: 7
            },
            email: {
                required: true,
            },
            company_delivery_site_id: {
                required: true,
            },
            student_type: {
                required: true,
            },
        };

        var tab1ValidationMessages = {
            salutation: "Please choose one salutation",
            first_name: "Please enter first name",
            family_name: "Please enter family name",
            date_of_birth: "Please enter date of birth",
            passport_no: "Please enter passport no",
            citizenship_country: "Please choose one citizenship country",
            phone: {
                digits: "Phone number must only be digits",
                minlength: "Phone number must be at least 7 characters long"
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number must only be digits",
                minlength: "Mobile number must be at least 7 characters long"
            },
            email: "Please enter email",
            company_delivery_site_id: "Please choose one site",
            student_type: "Please choose one type",
        };

        // Validation rules for tab 2
        var tab2ValidationRules = {
            province: {
                required: true,
            },
            district: {
                required: true,
            },
            local_body: {
                required: true,
            },
            ward: {
                required: true,
            },
            tole: {
                required: true,
            },
            address: {
                required: true,
            },
            emergency_contact_person: {
                required: true
            },
            emergency_contact_number: {
                required: true,
                digits: true,
                minlength: 7
            },
            emergency_contact_relation: {
                required: true
            },
        };

        // Validation messages for tab 2
        var tab2ValidationMessages = {
            province: "Please select one province",
            district: "Please select one district",
            local_body: "Please select one local body",
            ward: "Please select one ward",
            tole: "Please select one tole",
            address: "Please enter address",
            emergency_contact_person: "Please enter emergency contact person",
            emergency_contact_number: {
                required: "Please enter emergency contact number",
                digits: "Emergency contact number must only be digits",
                minlength: "Emergency contact number must be at least 7 characters long"
            },
            emergency_contact_relation: "Please enter emergency contact relation",
        };

        // Validation rules for tab 4
        var tab4ValidationRules = {
            salutation: {
                required: true,
            },
            first_name: {
                required: true,
            },
            family_name: {
                required: true,
            },
            date_of_birth: {
                required: true,
            },
            passport_no: {
                required: true,
            },
            citizenship_country: {
                required: true,
            },
            phone: {
                digits: true,
                minlength: 7
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 7
            },
            phone_work: {
                digits: true,
                minlength: 7
            },
            email: {
                required: true,
            },
            company_delivery_site_id: {
                required: true,
            },
            student_type: {
                required: true,
            },
            emergency_contact_person: {
                required: true
            },
            emergency_contact_number: {
                required: true,
                digits: true,
                minlength: 7
            },
            emergency_contact_relation: {
                required: true
            },
            status: {
                required: true
            },
        };

        // Validation rules for tab 4
        var tab4ValidationRules = {
            salutation: {
                required: true,
            },
            first_name: {
                required: true,
            },
            family_name: {
                required: true,
            },
            date_of_birth: {
                required: true,
            },
            passport_no: {
                required: true,
            },
            citizenship_country: {
                required: true,
            },
            phone: {
                digits: true,
                minlength: 7
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 7
            },
            phone_work: {
                digits: true,
                minlength: 7
            },
            email: {
                required: true,
            },
            company_delivery_site_id: {
                required: true,
            },
            student_type: {
                required: true,
            },
            province: {
                required: true,
            },
            district: {
                required: true,
            },
            local_body: {
                required: true,
            },
            ward: {
                required: true,
            },
            tole: {
                required: true,
            },
            address: {
                required: true,
            },
            emergency_contact_person: {
                required: true
            },
            emergency_contact_number: {
                required: true,
                digits: true,
                minlength: 7
            },
            emergency_contact_relation: {
                required: true
            },
            status: {
                required: true
            },
        };

        // Validation messages for tab 3
        var tab4ValidationMessages = {
            salutation: "Please choose one salutation",
            first_name: "Please enter first name",
            family_name: "Please enter family name",
            date_of_birth: "Please enter date of birth",
            passport_no: "Please enter passport no",
            citizenship_country: "Please choose one citizenship country",
            phone: {
                digits: "Phone number must only be digits",
                minlength: "Phone number must be at least 7 characters long"
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number must only be digits",
                minlength: "Mobile number must be at least 7 characters long"
            },
            email: "Please enter email",
            password: "Please enter password",
            image: "Please upload image",
            company_delivery_site_id: "Please choose one site",
            student_type: "Please choose one type",
            province: "Please select one province",
            district: "Please select one district",
            local_body: "Please select one local body",
            ward: "Please select one ward",
            tole: "Please select one tole",
            address: "Please enter address",
            emergency_contact_person: "Please enter emergency contact person",
            emergency_contact_number: {
                required: "Please enter emergency contact number",
                digits: "Emergency contact number must only be digits",
                minlength: "Emergency contact number must be at least 7 characters long"
            },
            emergency_contact_relation: "Please enter emergency contact relation",
            status: "Please select one status",
        };

        // Initialize form validation
        $('#multiStepForm').validate({
            ignore: [],
            rules: tab1ValidationRules, // Set initial rules for tab 1
            messages: tab1ValidationMessages, // Set initial messages for tab 1
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

        // Handle next button click
        $('.next').click(function() {
            var $activeTab = $('.tab-pane.active');
            var $nextTab = $activeTab.next('.tab-pane');

            if ($activeTab.attr('id') === 'personal') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            } else if ($activeTab.attr('id') === 'contact') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            } else if ($activeTab.attr('id') === 'agent') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            } else if ($activeTab.attr('id') === 'other') {
                if ($('#multiStepForm').valid()) {
                    $activeTab.removeClass('active show');
                    $nextTab.addClass('active show');
                    $('#tabNavigation a[href="#' + $nextTab.attr('id') + '"]').tab('show');
                }
            }
        });

        // Handle previous button click
        $('.prev').click(function() {
            var $activeTab = $('.tab-pane.active');
            var $prevTab = $activeTab.prev('.tab-pane');

            $activeTab.removeClass('active show');
            $prevTab.addClass('active show');
            $('#tabNavigation a[href="#' + $prevTab.attr('id') + '"]').tab('show');
        });

        // Handle tab change event
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var targetTab = $(e.target).attr("href"); // activated tab
            if (targetTab === '#contact') {
                // Set validation rules for tab 2
                // Set validation rules for tab 1
                $('#multiStepForm').validate().settings.rules = tab2ValidationRules;
                $('#multiStepForm').validate().settings.messages = tab2ValidationMessages;

                // $('#multiStepForm').valid(); // Trigger validation
            } else if (targetTab === '#personal') {
                // Set validation rules for tab 1
                $('#multiStepForm').validate().settings.rules = tab1ValidationRules;
                $('#multiStepForm').validate().settings.messages = tab1ValidationMessages;
                // $('#multiStepForm').valid(); // Trigger validation
            } else if (targetTab === '#other') {
                // Set validation rules for tab 4
                $('#multiStepForm').validate().settings.rules = tab4ValidationRules;
                $('#multiStepForm').validate().settings.messages = tab4ValidationMessages;
                // $('#multiStepForm').valid(); // Trigger validation
            }
        });

        // Handle submit button click
        $('.submit').click(function() {
            var $activeTab = $('.tab-pane.active');
            var $prevTab = $activeTab.prev('.tab-pane');

            if ($activeTab.attr('id') === 'other') {
                $('#multiStepForm').validate();
            }
        });
    });
</script>
@endsection
