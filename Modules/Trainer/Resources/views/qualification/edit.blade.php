@extends('user::layouts.master')
@section('title', 'Admin | Edit Teacher Qualification')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Teacher Qualification</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.qualification.index', $trainerQualification->id) }}">Qualifications</a></li>
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
        <form id="addtrainerqualification" action="{{ route('admin.trainer.qualification.update', $trainerQualification->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit {{ $trainerQualification->trainer->salutation }} {{ $trainerQualification->trainer->first_name }} {{ $trainerQualification->trainer->family_name }}'s Qualification</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="award_university">Award University</label> <span class="required">*</span>
                                    <select id="award_university" name="award_university" class="form-control">
                                        <option value="" selected disabled>-- Select University --</option>
                                        @foreach($universities as $key => $value)
                                        <option value="{{$key}}" @if ($value==$trainerQualification->award_university) selected @endif> {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <select name="name" id="name" class="form-control">
                                        @foreach($universityQualifications as $key => $value)
                                        <option value="{{ $key }}" @if ($value==$trainerQualification->name) selected @endif> {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="award_year">Award Year</label> <span class="required">*</span>
                                    <input type="date" name="award_year" class="form-control" id="award_year" placeholder="Enter Award Year" value="{{ $trainerQualification->award_year }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country_id">Country</label> <span class="required">*</span>
                                    <select class="form-control" name="country_id" id="country">
                                        <option value="">-- Select Country --</option>
                                        @foreach($countries as $key => $value)
                                        <option value="{{ $key }}" @if ($key==$trainerQualification->country_id) selected @endif> {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($trainerQualification->status == '1')selected @endif value="1">Active</option>
                                        <option @if($trainerQualification->status == '0')selected @endif value="0">Inactive</option>
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
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("Form successful submitted!");
        //     }
        // });
        $('#addtrainerqualification').validate({
            rules: {
                award_university: {
                    required: true,
                },
                name: {
                    required: true,
                },
                award_year: {
                    required: true,
                },
                country_id: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                award_university: "Please choose one university",
                name: "Please enter name",
                award_year: "Please enter year",
                country_id: "Please enter country",
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

    $('#award_university').change(function() {
        var universityID = $(this).val();
        if (universityID) {
            $.ajax({
                type: "GET",
                url: "{{url('admin/trainer/university')}}?university_id=" + universityID,
                success: function(res) {
                    if (res) {
                        $("#name").empty();
                        $("#name").append('<option value="">-- Select Name --</option>');
                        $.each(res, function(key, value) {
                            $("#name").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#name").empty();
                    }
                }
            });
        } else {
            $("#name").empty();
        }
    });
</script>
@endsection