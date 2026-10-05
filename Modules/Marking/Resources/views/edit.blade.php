@extends('user::layouts.master')
@section('title', 'Admin | Edit Marking Type')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Marking Type</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.marking-type.index') }}">Marking Types</a></li>
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
        <form id="editmarkingtype" action="{{ route('admin.marking-type.update', $type->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Marking Type</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $type->name }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="full_marks">Full Marks</label> <span class="required">*</span>
                                    <input type="number" name="full_marks" class="form-control" id="full_marks" placeholder="Enter Full Marks" value="{{ $type->full_marks }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pass_marks">Pass Marks</label> <span class="required">*</span>
                                    <input type="number" name="pass_marks" class="form-control" id="pass_marks" placeholder="Enter Pass Marks" value="{{ $type->pass_marks }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected disabled>-- Select Status --</option>
                                        <option @if($type->status == '1')selected @endif value="1">Active</option>
                                        <option @if($type->status == '0')selected @endif value="0">Inactive</option>
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
        $('#editmarkingtype').validate({
            rules: {
                name: {
                    required: true,
                },
                full_marks: {
                    required: true,
                    digits: true,
                },
                pass_marks: {
                    required: true,
                    digits: true,
                },
                status: {
                    required: true,
                },
            },
            messages: {
                name: "Please enter name",
                full_marks: {
                    required: "Please enter full marks",
                    digits: "Amount must only be digits",
                },
                pass_marks: {
                    required: "Please enter pass marks",
                    digits: "Amount must only be digits",
                },
                status: "Please choose one status",
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