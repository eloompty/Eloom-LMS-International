@extends('student::student.layouts.master')
@section('title', 'Student | Open Ticket')

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
                <h1>Open Ticket</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.ticket.index') }}">Tickets</a></li>
                    <li class="breadcrumb-item active">Open</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="ticket" action="{{ route('student.ticket.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Open a ticket for support</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="subject">Subject</label> <span class="required">*</span>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Please enter subject">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label> <span class="required">*</span>
                                <textarea name="description" id="description" class="form-control" placeholder="Please enter description"></textarea>
                            </div>
                            <div id="file-inputs">
                                <div class="form-group">
                                    <label for="file">Attachments</label>
                                    <input type="file" name="files[]" class="form-control mb-2">
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mb-2" id="add-input">Add Another File</button>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" onclick="return confirmation();">Submit</button>
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
        $('#ticket').validate({
            rules: {
                subject: {
                    required: true,
                },
                description: {
                    required: true,
                }
            },
            messages: {
                subject: "Please enter subject",
                description: "Please enter description",
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

    $(document).ready(function() {
        $('#add-input').click(function() {
            $('#file-inputs').append('<div class="form-group"><input type="file" name="files[]" class="form-control mb-2"><button type="button" class="btn btn-danger remove-input">Remove</button></div>');
        });

        $(document).on('click', '.remove-input', function() {
            $(this).closest('.form-group').remove();
        });
    });

    $('#addassignmentfile').submit(function(e) {
        let totalSize = 0;
        $('input[type="file"]').each(function() {
            if (this.files.length > 0) {
                totalSize += this.files[0].size;
            }
        });

        // Convert bytes to MB
        totalSize = totalSize / (1024 * 1024);
        console.log('ts:', totalSize);

        if (totalSize > 10) {
            e.preventDefault();
            alert('Total file size must not exceed 10 MB');
            $('#error-message').text('Total file size must not exceed 10 MB.');
        } else {
            $('#error-message').text('');
        }
    });
</script>
@endsection
