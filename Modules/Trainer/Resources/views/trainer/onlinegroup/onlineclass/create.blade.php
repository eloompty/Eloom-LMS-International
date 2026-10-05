@extends('trainer::trainer.layouts.master')
@section('title', 'Faculty | Create Group Online Class')

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
                <h1>Create Group Online Class</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('trainer.onlineclass.group.index') }}">Group Online Classes</a></li>
                    <li class="breadcrumb-item active">Create Group Online Class</li>
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
                        <h3 class="card-title">Create <small>Group Online Class</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addonlineclass" action="{{ route('trainer.onlineclass.group.class.store', $id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="topic">Topic</label> <span class="required">*</span>
                                <input type="text" name="topic" class="form-control" id="topic" placeholder="Enter Topic">
                            </div>
                            <div class="form-group">
                                <label for="agenda">Agenda</label> <span class="required">*</span>
                                <input type="text" name="agenda" class="form-control" id="agenda" placeholder="Enter Agenda">
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label> <span class="required">*</span>
                                <input type="date" name="date" class="form-control" id="date" placeholder="Enter Date">
                            </div>
                            <div class="form-group">
                                <label for="time">Time</label> <span class="required">*</span>
                                <input type="time" name="time" class="form-control" id="time" placeholder="Enter Time">
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
        $('#addonlineclass').validate({
            rules: {
                topic: {
                    required: true,
                },
                agenda: {
                    required: true,
                },
                date: {
                    required: true,
                },
                time: {
                    required: true,
                },
            },
            messages: {
                topic: "Please enter topic",
                agenda: "Please enter agenda",
                date: "Please enter date",
                time: "Please enter time",
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