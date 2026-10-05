@extends('user::layouts.master')
@section('title', 'Admin | Add Student')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($table=='offer' ) href="{{ route('admin.student.offer.index') }}" @else href="{{ route('admin.student.index') }}" @endif>Students</a></li>
                    <li class="breadcrumb-item active">Add</li>
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
                        <h3 class="card-title"> Add Student</h3>
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
                        <form id="multiStepForm" action="{{ route('admin.student.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="table" value="{{ $table }}">
                            <div class="tab-content" id="formTabs">
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <h3>Personal Information</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="salutation">Salutation</label> <span class="required">*</span>
                                            <select name="salutation" class="form-control" id="salutation">
                                                <option value="" selected disabled>-- Select Salutation --</option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Ms.">Ms.</option>
                                                <option value="Miss">Miss</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="first_name">First Name</label> <span class="required">*</span>
                                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="family_name">Family Name</label> <span class="required">*</span>
                                            <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="date_of_birth">Date of Birth</label> <span class="required">*</span>
                                            <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="mobile">Mobile</label> <span class="required">*</span>
                                            <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="citizenship_country">Citizenship Country</label> <span class="required">*</span>
                                            <select class="form-control" name="citizenship_country" id="citizenship_country">
                                                <option value="">-- Select Citizenship Country --</option>
                                                @foreach($countries as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-sm-3">
                                            <label for="email">Email</label> <span class="required">*</span>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="email_alternative">Email Alternative</label>
                                            <input type="text" name="email_alternative" class="form-control" id="email_alternative" placeholder="Enter Email Alternative">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="password">Password</label> <span class="required">*</span>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">-- Select Gender --</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="id_no">Student ID</label>
                                            <input type="text" name="id_no" class="form-control" id="id_no" placeholder="Enter Student Id" value="{{ $student_id_value ?? '' }}" @if($student_id_readonly ?? false) readonly @endif>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="status">Delivery Site</label> <span class="required">*</span>
                                            <select class="form-control" name="company_delivery_site_id">
                                                <option value="">-- Select Delivery Site --</option>
                                                @foreach($delivery_sites as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->site_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="allow_submission_after_due_date">Allow Assignment Submission After Due Date</label>
                                            <select name="allow_submission_after_due_date" class="form-control" id="allow_submission_after_due_date">
                                                <option value="" selected disabled>-- Select Allow Assignment Submission After Due Date --</option>
                                                <option value="on">On</option>
                                                <option value="off">Off</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="image">Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel">
                                    <h3>Current Address Information</h3>
                                    <div class="row">
                                        @include('partials.address-fields', ['address' => null, 'addressIdPrefix' => 'student'])
                                    </div>
                                    @include('partials.student-additional-addresses', ['student' => null])
                                    <hr>
                                    <div class="form-group">
                                        <div id="dynamicTable">
                                            <h5>Father Contact Information</h5>
                                            <div class="row mb-2">
                                                <input type="hidden" name="relation[]" class="form-control" placeholder="Relation" value="Father">
                                                <div class="col">
                                                    <input type="text" name="name[]" class="form-control" placeholder="Name">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="contact_no[]" class="form-control" placeholder="Contact No">
                                                </div>
                                                <div class="col">
                                                    <input type="info_email" name="info_email[]" class="form-control" placeholder="Email">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="occupation[]" class="form-control" placeholder="Occupation">
                                                </div>
                                                <div class="col">
                                                </div>
                                            </div>
                                            <br>
                                            <h5>Mother Contact Information</h5>
                                            <div class="row mb-2">
                                                <input type="hidden" name="relation[]" class="form-control" placeholder="Relation" value="Mother">
                                                <div class="col">
                                                    <input type="text" name="name[]" class="form-control" placeholder="Name">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="contact_no[]" class="form-control" placeholder="Contact No">
                                                </div>
                                                <div class="col">
                                                    <input type="info_email" name="info_email[]" class="form-control" placeholder="Email">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="occupation[]" class="form-control" placeholder="Occupation">
                                                </div>
                                                <div class="col">
                                                </div>
                                            </div>
                                            <br>
                                            <h5>Guardian Contact Information</h5>
                                            <div class="row mb-2">
                                                <input type="hidden" name="relation[]" class="form-control" placeholder="Relation" value="Guardian">
                                                <div class="col">
                                                    <input type="text" name="name[]" class="form-control" placeholder="Name">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="contact_no[]" class="form-control" placeholder="Contact No">
                                                </div>
                                                <div class="col">
                                                    <input type="info_email" name="info_email[]" class="form-control" placeholder="Email">
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="occupation[]" class="form-control" placeholder="Occupation">
                                                </div>
                                                <div class="col">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div>
                                <!-- <div class="tab-pane fade" id="agent" role="tabpanel">
                                    <h3>Agent</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <label for="agent_id">Agent</label>
                                            <select id="agent_id" name="agent_id" class="form-control">
                                                <option value="" selected disabled>-- Select Agent --</option>
                                                @foreach($agents as $key => $value)
                                                <option value="{{ $key }}"> {{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="branch_id">Branch</label>
                                            <select name="branch_id" id="branch_id" class="form-control">
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label for="user_id">User</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary prev">Previous</button>
                                    <button type="button" class="btn btn-primary next">Next</button>
                                </div> -->
                                <div class="tab-pane fade" id="other" role="tabpanel">
                                    <div class="form-group">
                                        <label for="status">Status</label> <span class="required">*</span>
                                        <select name="status" class="form-control" id="status">
                                            <option value="" disabled>-- Select Status --</option>
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    @if ($table == 'offer')
                                    <input type="hidden" name="is_enrolled" class="form-control" value="0">
                                    @endif
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
        var addRowButton = document.getElementById('addRow');
        var dynamicTable = document.getElementById('dynamicTable');

        if (addRowButton) {
            addRowButton.addEventListener('click', function() {
                addRow();
            });
        }

        if (!dynamicTable) return;

        dynamicTable.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('addRow')) {
                addRow();
            }
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
        var $form = $('#multiStepForm');
        var tabSelector = '#formTabs .tab-pane';

        var validator = $form.validate({
            ignore: ':hidden',
            rules: {
                salutation: {
                    required: true
                },
                first_name: {
                    required: true
                },
                family_name: {
                    required: true
                },
                date_of_birth: {
                    required: true
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
                citizenship_country: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                email_alternative: {
                    email: true
                },
                password: {
                    required: true
                },
                company_delivery_site_id: {
                    required: true
                },
                status: {
                    required: true
                },
                province: {
                    required: true
                },
                district: {
                    required: true
                },
                local_body: {
                    required: true
                },
                ward: {
                    required: true
                },
                tole: {
                    required: true
                },
                address: {
                    required: true
                },
                building_name: {
                    required: true
                },
                street_no: {
                    required: true
                },
                street_address: {
                    required: true
                },
                area: {
                    required: true
                },
                city: {
                    required: true
                },
                state: {
                    required: true
                },
                state_region: {
                    required: true
                },
                suburb: {
                    required: true
                },
                emirate: {
                    required: true
                },
                address_line_1: {
                    required: true
                },
                postal_code: {
                    required: true
                },
                zip_code: {
                    required: true
                },
                country_id: {
                    required: true
                }
            },
            messages: {
                salutation: "Please choose one salutation",
                first_name: "Please enter first name",
                family_name: "Please enter family name",
                date_of_birth: "Please enter date of birth",
                phone: {
                    digits: "Phone number must only be digits",
                    minlength: "Phone number must be at least 7 characters long"
                },
                mobile: {
                    required: "Please enter mobile number",
                    digits: "Mobile number must only be digits",
                    minlength: "Mobile number must be at least 7 characters long"
                },
                citizenship_country: "Please choose one citizenship country",
                email: {
                    required: "Please enter email",
                    email: "Please enter a valid email address"
                },
                email_alternative: "Please enter a valid alternative email address",
                password: "Please enter password",
                company_delivery_site_id: "Please choose one site",
                status: "Please select one status",
                province: "Please select one province",
                district: "Please select one district",
                local_body: "Please select one local body",
                ward: "Please select one ward",
                tole: "Please select one tole",
                address: "Please enter address",
                building_name: "Please enter building name",
                street_no: "Please enter street number",
                street_address: "Please enter street address",
                area: "Please enter area",
                city: "Please enter city",
                state: "Please enter state",
                state_region: "Please enter state or region",
                suburb: "Please enter suburb",
                emirate: "Please enter emirate",
                address_line_1: "Please enter address line 1",
                postal_code: "Please enter postal code",
                zip_code: "Please enter ZIP code",
                country_id: "Please select country"
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                var $formGroup = element.closest('.form-group');
                error.addClass('invalid-feedback');

                if ($formGroup.length) {
                    $formGroup.append(error);
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#dynamicTable input[name="info_email[]"]').each(function() {
            $(this).rules('add', {
                email: true,
                messages: {
                    email: "Please enter a valid email address"
                }
            });
        });

        function getTabFields($tab) {
            return $tab.find(':input').filter(function() {
                return this.name && !this.disabled && this.type !== 'hidden';
            });
        }

        function validateTab($tab) {
            var isValid = true;
            var previousIgnore = validator.settings.ignore;

            validator.settings.ignore = '';
            getTabFields($tab).each(function() {
                if (!validator.element(this)) {
                    isValid = false;
                }
            });
            validator.settings.ignore = previousIgnore;

            return isValid;
        }

        function showTab($tab) {
            $('#tabNavigation a[href="#' + $tab.attr('id') + '"]').tab('show');
        }

        function firstInvalidTab() {
            var $invalidTab = $();

            $(tabSelector).each(function() {
                var $tab = $(this);

                if (!validateTab($tab) && !$invalidTab.length) {
                    $invalidTab = $tab;
                }
            });

            return $invalidTab;
        }

        $('#tabNavigation a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('.next').on('click', function() {
            var $activeTab = $('.tab-pane.active');
            var $nextTab = $activeTab.next('.tab-pane');

            if (!$nextTab.length || !validateTab($activeTab)) {
                return;
            }

            showTab($nextTab);
        });

        $('.prev').on('click', function() {
            var $activeTab = $('.tab-pane.active');
            var $prevTab = $activeTab.prev('.tab-pane');

            if ($prevTab.length) {
                showTab($prevTab);
            }
        });

        $form.on('submit', function(e) {
            var $invalidTab = firstInvalidTab();

            if ($invalidTab.length) {
                e.preventDefault();
                showTab($invalidTab);
            }
        });
    });
</script>
@endsection
