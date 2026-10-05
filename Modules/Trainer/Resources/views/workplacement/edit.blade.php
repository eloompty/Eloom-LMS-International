@extends('user::layouts.master')
@section('title', 'Admin | Edit Teacher Work Placement')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Teacher Work Placement</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.workplacement.index', $trainerWork->type_id) }}">Work Placements</a></li>
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
        <form id="edittrainerworkplacement" action="{{ route('admin.trainer.workplacement.update', $trainerWork->type_id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ $trainerWork->trainer->salutation }} {{ $trainerWork->trainer->first_name }} {{ $trainerWork->trainer->family_name }}'s Work Placement</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="placement_company_name">Placement Company Name</label> <span class="required">*</span>
                                    <input type="text" name="placement_company_name" class="form-control" id="placement_company_name" placeholder="Enter Placement Company Name" value="{{ $trainerWork->placement_company_name }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contact_person">Contact Person</label> <span class="required">*</span>
                                    <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Enter Contact Person" value="{{ $trainerWork->contact_person }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contact_person_mobile">Contact Person Mobile</label> <span class="required">*</span>
                                    <input type="text" name="contact_person_mobile" class="form-control" id="contact_person_mobile" placeholder="Enter Contact Person Mobile" value="{{ $trainerWork->contact_person_mobile }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="position">Position</label> <span class="required">*</span>
                                    <input type="text" name="position" class="form-control" id="position" placeholder="Enter Position" value="{{ $trainerWork->position }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="placement_hours">Placement Hours</label> <span class="required">*</span>
                                    <input type="text" name="placement_hours" class="form-control" id="placement_hours" placeholder="Enter Placement Hours" value="{{ $trainerWork->placement_hours }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="placement_description">Placement Description</label> <span class="required">*</span>
                                    <textarea class="form-control" rows="3" placeholder="Enter Placement Description" name="placement_description">{{ $trainerWork->placement_description }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="site_name">Site Name</label> <span class="required">*</span>
                                    <input type="text" name="site_name" class="form-control" id="site_name" placeholder="Enter Site Name" value="{{ $trainerWork->site_name }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $trainerWork->starting_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ending_date">Ending Date</label> <span class="required">*</span>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date" value="{{ $trainerWork->ending_date }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($trainerWork->status == '1')selected @endif value="1">Active</option>
                                        <option @if($trainerWork->status == '0')selected @endif value="0">Inactive</option>
                                    </select>
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
        $('#edittrainerworkplacement').validate({
            rules: {
                placement_company_name: {
                    required: true,
                },
                contact_person: {
                    required: true,
                },
                contact_person_mobile: {
                    required: true,
                },
                position: {
                    required: true,
                },
                placement_hours: {
                    required: true,
                },
                placement_description: {
                    required: true,
                },
                site_name: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                ending_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                placement_company_name: "Please enter placement company name",
                contact_person: "Please enter contact person",
                contact_person_mobile: "Please enter contact person mobile number",
                position: "Please enter position",
                placement_hours: "Please enter placement hours",
                placement_description: "Please enter placement description",
                site_name: "Please enter site name",
                starting_date: "Please enter starting date",
                ending_date: "Please enter ending date",
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