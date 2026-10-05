@extends('user::layouts.master')
@section('title', 'Admin | Copy Intake')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Copy Intake</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item active">Copy Intake</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="copyintake" action="{{ route('admin.intake.copy.create', $intake->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Copy Intake</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $intake->name }}">
                            </div>
                            <div class="form-group">
                                <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name" value="{{ $intake->reference_name }}">
                            </div>
                            <div class="form-group">
                                <label for="orientation_date">Orientation Date</label> <span class="required">*</span>
                                <input type="date" name="orientation_date" class="form-control" id="orientation_date" placeholder="Enter Orientation Date" value="{{ $intake->orientation_date }}">
                            </div>
                            <div class="form-group">
                                <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date" value="{{ $intake->starting_date }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($intake->status == '1')selected @endif value="1">Active</option>
                                    <option @if($intake->status == '0')selected @endif value="0">Inactive</option>
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

<script>
    $(function() {
        $('#copyintake').validate({
            rules: {
                duration: {
                    required: true,
                },
                orientation_date: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                duration: "Please enter duration",
                orientation_date: "Please enter orientation date",
                starting_date: "Please enter starting date",
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