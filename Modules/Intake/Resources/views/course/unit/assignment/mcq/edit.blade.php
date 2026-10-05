@extends('user::layouts.master')
@section('title', 'Admin | Edit Assignment MCQ')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Assignment MCQ</h1>
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
                        <h3 class="card-title">Edit <small>Multiple Choice Question</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="editassignmentmcq" action="{{ route('admin.intake.unit.assignment.mcq.update', [$question->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="question">Question</label> <span class="required">*</span>
                                <input type="text" name="question" class="form-control" id="question" placeholder="Enter Question" value="{{ $question->question }}">
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    @foreach($choices as $index => $value)
                                    <div id="row">
                                        <div class="input-group m-3">
                                            <label for="question">Choice: </label> <span class="required">*</span>
                                            <input type="hidden" name="choice_id[]" value="{{ $value->id }}">
                                            <input type="text" class="form-control m-input" name="choice[]" placeholder="Enter Choice" value="{{ $value->choice }}">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-danger" id="DeleteRow" type="button">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group m-3">
                                                <label for="is_correct">IsCorrect </label> <span class="required">*</span>
                                                <select name="is_correct[]" class="form-control" id="is_correct">
                                                    <option @if($value->is_correct == '1')selected @endif value="1">True</option>
                                                    <option @if($value->is_correct == '0')selected @endif value="0">False</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div id="newinput"></div>
                                    <button id="rowAdder" type="button" class="btn btn-dark">
                                        <span class="bi bi-plus-square-dotted">
                                        </span> ADD Choice
                                    </button>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($question->status == '1')selected @endif value="1">Active</option>
                                    <option @if($question->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">Submit</button>
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
        $('#editassignmentmcq').validate({
            rules: {
                question: {
                    required: true,
                },
                "choice[]": {
                    required: true,
                },
                "is_correct[]": {
                    required: true,
                }
            },
            messages: {
                question: "Please enter question",
                "choice[]": "Please enter choices",
                "is_correct[]": "Please choose true/false",
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
            '<label for="choice">Choice: </label>' +
            '<input type="text" class="form-control m-input" name="choice[]" placeholder="Enter Choice">' +
            '<div class="input-group-prepend">' +
            '<button class="btn btn-danger" id="DeleteRow" type="button">' +
            '<i class="bi bi-trash"></i> Delete</button> </div> </div>' +
            '<div class="col-md-2"> <div class="input-group m-3">' +
            '<label for="is_correct">IsCorrect</label> <span class="required">*</span>' +
            '<select name="is_correct[]" class="form-control">' +
            '<option value="0">False</option>' +
            '<option value="1">True</option>' +
            '</select>' +
            '</div>  </div> </div>'

        $('#newinput').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
    })
</script>
@endsection