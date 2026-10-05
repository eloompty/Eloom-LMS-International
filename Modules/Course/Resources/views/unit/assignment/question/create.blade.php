@extends('user::layouts.master')
@section('title', 'Admin | Add Assignment Question')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Assignment Question</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

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
                        <h3 class="card-title">Add <small>Question</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addassignmentquestion" action="{{ route('admin.course.unit.assignment.question.store', [$unit_assignment->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <div id="row">
                                        <div class="input-group m-3">
                                            <label for="question">Question: </label> <span class="required">*</span>
                                            <input type="text" class="form-control m-input" name="question[]" placeholder="Enter Question">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-danger" id="DeleteRow" type="button">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newinput"></div>
                                    <button id="rowAdder" type="button" class="btn btn-dark">
                                        <span class="bi bi-plus-square-dotted">
                                        </span> ADD Question
                                    </button>
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
        $('#addassignmentquestion').validate({
            rules: {
                "question[]": {
                    required: true,
                }
            },
            messages: {
                "question[]": "Please add question",
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

    $("#rowAdder").click(function() {
        newRowAdd =
            '<div id="row"> <div class="input-group m-3">' +
            '<label for="question">Question: </label> <span class="required">*</span>' +
            '<input type="text" class="form-control m-input" name="question[]" placeholder="Enter Question">' +
            '<div class="input-group-prepend">' +
            '<button class="btn btn-danger" id="DeleteRow" type="button">' +
            '<i class="bi bi-trash"></i> Delete</button> </div> </div> </div>'

        $('#newinput').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
    })

</script>
@endsection