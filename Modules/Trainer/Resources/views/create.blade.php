@extends('user::layouts.master')
@section('title', 'Admin | Add Faculty/Teacher')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Faculty/Teacher</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
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
        <form id="addtrainer" action="{{ route('admin.trainer.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Teacher</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <h3>Personal Information </h3>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="salutation">Salutation</label> <span class="required">*</span>
                                        <select name="salutation" class="form-control" id="salutation">
                                            <option value="" selected disabled>-- Select Salutation --</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Miss">Miss</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label> <span class="required">*</span>
                                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="family_name">Family Name</label> <span class="required">*</span>
                                        <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth</label> <span class="required">*</span>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="phone">Phone</label> <span class="required">*</span>
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label> <span class="required">*</span>
                                        <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="id_no">Teacher ID</label>
                                        <input type="text" name="id_no" class="form-control" id="id_no" placeholder="Enter Teacher ID" value="{{ $teacher_id_value ?? '' }}" @if($teacher_id_readonly ?? false) readonly @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="email">Email</label> <span class="required">*</span>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="password">Password</label> <span class="required">*</span>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 80px; border: #ebebeb 1px solid;">
                                    </div>
                                </div>
                            </div>
                            </br>
                            <h3>Address Information</h3>
                            <div class="row">
                                @include('partials.address-fields', ['address' => null, 'addressIdPrefix' => 'trainer'])
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" disabled>-- Select Status --</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
        $('#addtrainer').validate({
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
                phone: {
                    required: true,
                    minlength: 7
                },
                mobile: {
                    required: true,
                    minlength: 7
                },
                email: {
                    required: true
                },
                country_id: {
                    required: true
                },
                status: {
                    required: true
                },
                password: {
                    required: true
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
            },
            messages: {
                salutation: "Please choose one salutation",
                first_name: "Please enter first name",
                family_name: "Please enter family name",
                date_of_birth: "Please enter date of birth",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                mobile: {
                    required: "Please enter mobile number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                email: "Please enter email",
                image: "Please upload image",
                country_id: "Please enter country",
                status: "Please select one status",
                password: "Please enter password",
                province: "Please select one province",
                district: "Please select one district",
                local_body: "Please select one local body",
                ward: "Please select one ward",
                tole: "Please select one tole",
                address: "Please enter address",
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
</script>
@endsection