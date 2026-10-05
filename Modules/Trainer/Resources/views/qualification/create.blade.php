@extends('user::layouts.master')
@section('title', 'Admin | Add Teacher Qualification')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Teacher Qualification</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.index') }}">Teachers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trainer.qualification.index', $trainer->id) }}">Qualifications</a></li>
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
        <form id="addtrainerqualification" action="{{ route('admin.trainer.qualification.store', $trainer->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ $trainer->salutation }} {{ $trainer->first_name }} {{ $trainer->family_name }}'s Qualification</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4" id="billdesc">
                                    <label for="award_university">Award University</label> <span class="required">*</span>
                                    <select id="award_university" class="form-control" name="award_university">
                                        <option value="" selected disabled>-- Select University --</option>
                                        @foreach($universities as $key => $value)
                                        <option value="{{$key}}"> {{$value}}</option>
                                        @endforeach
                                        <option class="editable" value="other">Other</option>
                                    </select>
                                    <input class="form-control editOption" style="display:none;" name="university" value="Other"></input>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <select name="name" id="name" class="form-control">
                                        <option value="" selected disabled>-- Select Name --</option>
                                        <option class="editQualification" value="other">Other</option>
                                    </select>
                                    <input class="form-control editName" style="display:none;" name="qualification" value="Other"></input>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="award_year">Award Year</label> <span class="required">*</span>
                                    <input type="date" name="award_year" class="form-control" id="award_year" placeholder="Enter Award Year">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country_id">Country</label> <span class="required">*</span>
                                    <select class="form-control" name="country_id" id="country">
                                        <option value="">-- Select Country --</option>
                                        @foreach($countries as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
        $('#addtrainerqualification').validate({
            rules: {
                award_university: {
                    required: true,
                },
                university: {
                    required: true,
                },
                name: {
                    required: true,
                },
                qualification: {
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
                award_university: "Please choose university",
                university: "Please enter university",
                name: "Please choose name",
                qualification: "Please enter name",
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
                        $("#name").append('<option class="editQualification" value="other">Other</option>');

                    } else {
                        $("#name").empty();
                    }
                }
            });
        } else {
            $("#name").empty();
        }
    });

    var initialText = $('.editable').val();
    $('editOption').val(initialText);

    $('#award_university').change(function() {
        var selected = $('option:selected', this).attr('class');
        var optionText = $('.editable').text();

        if (selected == "editable") {
            $('.editOption').show();

            $('.editOption').keyup(function() {
                var editText = $('.editOption').val();
                $('.editable').val(editText);
                $('.editable').html(editText);
            });

        } else {
            $('.editOption').hide();
        }
    });

    $('#name').change(function() {
        var selected = $('option:selected', this).attr('class');
        var optionText = $('.editQualification').text();

        if (selected == "editQualification") {
            $('.editName').show();

            $('.editName').keyup(function() {
                var editText = $('.editName').val();
                $('.editQualification').val(editText);
                $('.editQualification').html(editText);
            });

        } else {
            $('.editName').hide();
        }
    });
</script>
@endsection