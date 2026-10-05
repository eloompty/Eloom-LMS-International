@extends('agent::agent.layouts.master')
@section('title', 'Agent | Add Branch')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Branch</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('agent.branch.index') }}">Branches</a></li>
                    <li class="breadcrumb-item active">Add Branch</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addbranch" action="{{ route('agent.branch.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add  Branch</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="country_id">Country</label> <span class="required">*</span>
                                <select class="form-control" name="country_id">
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label> <span class="required">*</span>
                                <input type="text" name="city" class="form-control" id="city" placeholder="Enter City">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label> <span class="required">*</span>
                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label> <span class="required">*</span>
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                            </div>
                            <div class="form-group">
                                <label for="rate">Rate</label> <span class="required">*</span>
                                <input type="number" name="rate" class="form-control" id="rate" placeholder="Enter Rate">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
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
        $('#addbranch').validate({
            rules: {
                name: {
                    required: true,
                },
                country_id: {
                    required: true,
                },
                city: {
                    required: true,
                },
                address: {
                    required: true,
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 7
                },
                rate: {
                    required: true,
                    digits: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
                country_id: "Please choose one country",
                city: "Please enter city",
                address: "Please enter address",
                phone: {
                    required: "Please enter phone number",
                    minlength: "Your phone number must be at least 7 characters long"
                },
                rate: "Please enter rate",
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