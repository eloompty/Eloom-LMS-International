@extends('user::layouts.master')
@section('title', 'Admin | Edit Company Delivery Site')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Company Delivery Sites</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.company.delivery.index') }}">Company Delivery Sites</a></li>
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
                        <h3 class="card-title">Edit <small>Company Delivery Site</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editdeliverysite" action="{{ route('admin.company.delivery.update', $delivery_site->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="site_name">Site Name</label> <span class="required">*</span>
                                        <input type="text" name="site_name" class="form-control" id="site_name" placeholder="Enter Site Name" value="{{ $delivery_site->site_name }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label> <span class="required">*</span>
                                        <input type="number" name="phone" class="form-control" id="phone" placeholder="Enter Phone" value="{{ $delivery_site->phone }}">
                                    </div>
                                </div>
                                @include('partials.address-fields', ['address' => $delivery_site->address, 'addressIdPrefix' => 'delivery_site'])
                                <div class="form-group col-sm-3">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($delivery_site->status == '1')selected @endif value="1">Active</option>
                                        <option @if($delivery_site->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
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
        $('#editdeliverysite').validate({
            rules: {
                site_name: {
                    required: true,
                },
                phone: {
                    required: true,
                    minlength: 7
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
                status: {
                    required: true
                },
            },
            messages: {
                site_name: "Please enter site name",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                status: "Please select one status",
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
