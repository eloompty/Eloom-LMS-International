@extends('agent::agent.layouts.master')
@section('title', 'Agent | Add Student')

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
                <h1>Add Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('agent.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Add Student</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addstudent" action="{{ route('agent.student.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Student</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <h3>Personal Information </h3>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="salutation">Salutation</label> <span class="required">*</span>
                                    <select name="salutation" class="form-control" id="salutation">
                                        <option value="" selected disabled>-- Select Salutation --</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="first_name">First Name</label> <span class="required">*</span>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="family_name">Family Name</label> <span class="required">*</span>
                                    <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="date_of_birth">Date of Birth</label> <span class="required">*</span>
                                    <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mobile">Mobile</label> <span class="required">*</span>
                                    <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="passport_no">Passport No</label> <span class="required">*</span>
                                    <input type="text" name="passport_no" class="form-control" id="passport_no" placeholder="Enter Passport No">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="citizenship">Citizenship</label> <span class="required">*</span>
                                    <input type="text" name="citizenship" class="form-control" id="citizenship" placeholder="Enter Citizenship">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="citizenship_country">Citizenship Country</label>
                                    <select class="form-control" name="citizenship_country" id="citizenship_country">
                                        <option value="">-- Select Citizenship Country --</option>
                                        @foreach(getIdentifiers('COUNTRY IDENTIFIER') as $citizenship_country)
                                        <option value="{{ $citizenship_country->value }}">{{ $citizenship_country->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="email">Email</label> <span class="required">*</span>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="email_alternative">Email Alternative</label>
                                    <input type="text" name="email_alternative" class="form-control" id="email_alternative" placeholder="Enter Email Alternative">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="gender">Gender</label> <span class="required">*</span>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">-- Select Gender --</option>
                                        @foreach(getIdentifiers('GENDER') as $gender)
                                        <option value="{{ $gender->value }}">{{ $gender->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="status">Delivery Site</label> <span class="required">*</span>
                                    <select class="form-control" name="company_delivery_site_id">
                                        <option value="">-- Select Delivery Site --</option>
                                        @foreach($delivery_sites as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="image">Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="student_type">Student Type</label> <span class="required">*</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="student_type" value="onshore" id="type_onshore" checked>
                                        <label class="form-check-label" for="type_onshore">
                                            Onshore
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="student_type" value="offshore" id="type_offshore">
                                        <label class="form-check-label" for="type_offshore">
                                            Offshore
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="onshore">
                                <hr>
                                <h3>Current Address Information</h3>
                                <div class="row">
                                    @include('partials.address-fields', ['address' => null, 'addressIdPrefix' => 'agent_student'])
                                </div>
                            </div>
                            <hr>
                            <h3>Overseas Address</h3>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="overseas_address">Overseas Address</label> <span class="required">*</span>
                                    <textarea id="overseas_address" name="overseas_address" class="form-control" placeholder="Enter Overseas Address"></textarea>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="overseas_country_id">Overseas Country</label> <span class="required">*</span>
                                    <select class="form-control" name="overseas_country_id" id="overseas_country_id">
                                        <option value="">-- Select Overseas Country --</option>
                                        @foreach($countries as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h3>Emergency Contact Information</h3>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="emergency_contact_person">Emergency Contact Person</label> <span class="required">*</span>
                                    <input type="text" name="emergency_contact_person" class="form-control" id="emergency_contact_person" placeholder="Enter Emergency Contact Person">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="emergency_contact_number">Emergency Contact Number</label> <span class="required">*</span>
                                    <input type="text" name="emergency_contact_number" class="form-control" id="emergency_contact_number" placeholder="Enter Emergency Contact Number">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="emergency_contact_relation">Emergency Contact Relation</label> <span class="required">*</span>
                                    <input type="text" name="emergency_contact_relation" class="form-control" id="emergency_contact_relation" placeholder="Enter Emergency Contact Relation">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <input type="hidden" id="address" name="current_address" />
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

<script>
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

    function myChangeFunction(input1) {
        var address = document.getElementById('address');
        address.value = input1.value;

        console.log('address: ', address.value);
    }

    $(document).ready(function() {
        var validator = $('#addstudent').validate({
            rules: {
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
                citizenship: {
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
                email: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                company_delivery_site_id: {
                    required: true,
                },
                student_type: {
                    required: true,
                },
                building_number: {
                    maxlength: 50
                },
                flat_unit: {
                    maxlength: 30
                },
                street_no: {
                    required: true,
                    maxlength: 15
                },
                street_address: {
                    required: true,
                    maxlength: 70
                },
                suburb: {
                    required: true,
                    maxlength: 50
                },
                state: {
                    required: false
                },
                zip_code: {
                    required: true,
                    maxlength: 4
                },
                country_id: {
                    required: false
                },
                overseas_address: {
                    required: true
                },
                overseas_country_id: {
                    required: true
                },
                address: {
                    required: true
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
            },
            messages: {
                salutation: "Please choose one salutation",
                first_name: "Please enter first name",
                family_name: "Please enter family name",
                date_of_birth: "Please enter date of birth",
                passport_no: "Please enter passport no",
                citizenship: "Please enter citizenship",
                phone: {
                    required: "Please enter phone number",
                    digits: "Phone number must only be digits",
                    minlength: "Phone number must be at least 7 characters long"
                },
                mobile: {
                    digits: "Mobile number must only be digits",
                    minlength: "Mobile number must be at least 7 characters long"
                },
                email: "Please enter email",
                company_delivery_site_id: "Please chose one site",
                building_number: {
                    maxlength: "Building Name cannot be longer than 50 characters"
                },
                flat_unit: {
                    maxlength: "Flat unit cannot be longer than 30 characters"
                },
                street_no: {
                    required: "Please enter street number",
                    maxlength: "Street Address cannot be longer than 15 characters"
                },
                street_address: {
                    required: "Please enter street address",
                    maxlength: "Street Address cannot be longer than 70 characters"
                },
                suburb: {
                    required: "Please enter suburb",
                    maxlength: "Street Address cannot be longer than 50 characters"
                },
                state: "Please enter state",
                zip_code: {
                    required: "Please enter postal code",
                    maxlength: "Postal cannot be longer than 4 characters"
                },
                country_id: "Please enter country",
                overseas_address: "Please enter overseas address",
                overseas_country_id: "Please enter overseas country",
                emergency_contact_person: "Please enter emergency contact person",
                emergency_contact_number: {
                    required: "Please enter emergency contact number",
                    digits: "Emergency contact number must only be digits",
                    minlength: "Emergency contact number must be at least 7 characters long"
                },
                emergency_contact_relation: "Please enter emergency contact relation",
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

        $("#addstudent").submit(function(event) {
            // Prevent the form from submitting
            event.preventDefault();

            var address = document.getElementById('address');

            console.log(address.value.length);
            if (address.value.length == 0) {
                console.log('no address');
                validator.settings.rules.country_id.required = false;
                validator.settings.rules.state.required = false;
                validator.settings.rules.suburb.required = false;
                validator.settings.rules.street_address.required = false;
                validator.settings.rules.street_no.required = false;
                validator.settings.rules.zip_code.required = false;
                if ($('#addstudent').valid()) {
                    this.submit();
                }
            } else if (address.value.length > 0) {
                console.log('yes address');
                validator.settings.rules.country_id.required = true;
                validator.settings.rules.state.required = true;
                validator.settings.rules.suburb.required = true;
                validator.settings.rules.street_address.required = true;
                validator.settings.rules.street_no.required = true;
                validator.settings.rules.zip_code.required = true;
                if ($('#addstudent').valid()) {
                    this.submit();
                }
            }

        });
    });
</script>
@endsection
