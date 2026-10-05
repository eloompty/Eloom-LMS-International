@extends('user::layouts.master')
@section('title', 'Admin | Add Lead')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Lead</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.lead.index') }}">Leads</a></li>
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
        <form id="addlead" action="{{ route('admin.lead.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Lead</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="company">Company</label>
                                    <input type="text" name="company" class="form-control" id="company" placeholder="Enter Company">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="first_name">First Name</label> <span class="required">*</span>
                                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter First Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="family_name">Family Name</label> <span class="required">*</span>
                                    <input type="text" name="family_name" class="form-control" id="family_name" placeholder="Enter Family Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="phone">Phone</label> <span class="required">*</span>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="lead_status">Lead Status</label> <span class="required">*</span>
                                    <select name="lead_status" class="form-control" id="lead_status">
                                        <option value="" selected disabled>-- Select Lead Status --</option>
                                        <option value="Attempted to Contact">Attempted to Contact</option>
                                        <option value="Contact in Futute">Contact in Futute</option>
                                        <option value="Contacted">Contacted</option>
                                        <option value="Junk Lead">Junk Lead</option>
                                        <option value="Lost Lead">Lost Lead</option>
                                        <option value="Not Contacted">Not Contacted</option>
                                        <option value="Pre-Qualified">Pre-Qualified</option>
                                        <option value="Not Qualified">Not Qualified</option>
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
        $('#addlead').validate({
            rules: {
                first_name: {
                    required: true,
                },
                family_name: {
                    required: true,
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 7
                },
                lead_status: {
                    required: true,
                },
            },
            messages: {
                first_name: "Please enter first name",
                family_name: "Please enter family name",
                phone: {
                    required: "Please enter phone number",
                    digits: "Phone number must only be digits",
                    minlength: "Phone number must be at least 7 characters long"
                },
                lead_status: "Please choose one lead status",
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