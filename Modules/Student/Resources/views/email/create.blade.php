@extends('user::layouts.master')
@section('title', 'Admin | Add Student Email')

@section('content')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Student Email</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.email.index', $student->id) }}">Emails</a></li>
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
        <form id="studentemail" action="{{ route('admin.student.email.store', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ userName('Student', $student->id) }}'s Email</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="email_id">Email</label> <span class="required">*</span>
                                    <select id="email_id" name="email_id" class="form-control">
                                        <option value="" selected disabled>-- Select Email --</option>
                                        @foreach($emails as $key => $value)
                                        <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="email_template_id">Email Template</label> <span class="required">*</span>
                                    <select id="email_template_id" name="email_template_id" class="form-control">
                                        <option value="" selected disabled>-- Select Email Template --</option>
                                        @foreach($templates as $key => $value)
                                        <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="email_fields" style="display: none;">
                                <div class="form-group col-sm-12">
                                    <label for="email_subject">Email Subject</label>
                                    <input type="text" class="form-control" id="email_subject" name="email_subject" value="">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="email_content">Email Content</label>
                                    <textarea name="email_content" class="form-control" id="email_content" placeholder="Enter Content"></textarea>
                                    <script>
                                        CKEDITOR.replace('email_content');
                                    </script>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="email_regards_name">Regards Name</label>
                                        <input type="text" class="form-control" id="email_regards_name" name="email_regards_name" value="">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="email_regards_position">Regards Position</label>
                                        <input type="text" class="form-control" id="email_regards_position" name="email_regards_position" value="">
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <input type="button" value="Edit Email content" id="edit_email" class="btn btn-info btn-sm" onclick="showEmail()" />
                            </div>
                            <div class="form-group preview">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-xl">
                                    Preview Email
                                </button>
                                <div class="modal fade" id="modal-xl">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Preview</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <head>
                                                            <style>
                                                                #outlook a {
                                                                    padding: 0
                                                                }

                                                                body {
                                                                    width: 100% !important;
                                                                    background-color: #333;
                                                                    -webkit-text-size-adjust: none;
                                                                    -ms-text-size-adjust: none;
                                                                    margin: 0 !important;
                                                                    padding: 0 !important
                                                                }

                                                                .ReadMsgBody {
                                                                    width: 100%
                                                                }

                                                                .ExternalClass {
                                                                    width: 100%
                                                                }

                                                                ol li {
                                                                    margin-bottom: 15px
                                                                }

                                                                img {
                                                                    height: auto;
                                                                    line-height: 100%;
                                                                    outline: none;
                                                                    text-decoration: none
                                                                }

                                                                #backgroundTable {
                                                                    height: 100% !important;
                                                                    margin: 0;
                                                                    padding: 0;
                                                                    width: 100% !important
                                                                }

                                                                p {
                                                                    margin: 1em 0
                                                                }

                                                                h1,
                                                                h2,

                                                                h4,
                                                                h5,
                                                                h6 {
                                                                    color: #222 !important;
                                                                    font-family: Arial, Helvetica, sans-serif;
                                                                    line-height: 100% !important
                                                                }

                                                                h3 {
                                                                    font-family: Arial, Helvetica, sans-serif;
                                                                    line-height: 100% !important
                                                                }

                                                                table td {
                                                                    border-collapse: collapse
                                                                }

                                                                .yshortcuts,
                                                                .yshortcuts a,
                                                                .yshortcuts a:link,
                                                                .yshortcuts a:visited,
                                                                .yshortcuts a:hover,
                                                                .yshortcuts a span {
                                                                    color: #000;
                                                                    text-decoration: none !important;
                                                                    border-bottom: none !important;
                                                                    background: none !important
                                                                }

                                                                .im {
                                                                    color: #000
                                                                }

                                                                div[id='tablewrap'] {
                                                                    width: 100%;
                                                                    max-width: 600px !important
                                                                }

                                                                table[class='fulltable'],
                                                                td[class='fulltd'] {
                                                                    max-width: 100% !important;
                                                                    width: 100% !important;
                                                                    height: auto !important
                                                                }

                                                                @media screen and (max-device-width: 430px),
                                                                screen and (max-width: 430px) {
                                                                    td[class=emailcolsplit] {
                                                                        width: 100% !important;
                                                                        float: left !important;
                                                                        padding-left: 0 !important;
                                                                        max-width: 430px !important
                                                                    }

                                                                    td[class=emailcolsplit] img {
                                                                        margin-bottom: 20px !important
                                                                    }
                                                                }
                                                            </style>
                                                        </head>

                                                        <body style='width:100% !important; height: 100% !important; margin:0 !important; padding:0 !important; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:#FFFFFF;'>
                                                            <table border='0' cellpadding='0' align='center' cellspacing='0' id='backgroundTable' style='height: 100% !important; margin:0; padding:0; width:100% !important; background-color:#333; color:#222222;'>
                                                                <tr>
                                                                    <td>
                                                                        <div id='tablewrap' align='center' style='width:100% !important; max-width:600px !important; text-align:center !important; margin-top:0 !important; margin-right: auto !important; margin-bottom:0 !important; margin-left: auto !important;'>
                                                                            <table align='center' border='0' cellpadding='0' cellspacing='0' id='contenttable' style='background-color:#FFFFFF; text-align:center !important; margin-top:0 !important; margin-right: auto !important; margin-bottom:0 !important; margin-left: auto !important; border:none; width: 100% !important; max-width:600px !important;' width='600'>
                                                                                <tr>
                                                                                    <td width='100%'>
                                                                                        <table bgcolor='#FFFFFF' border='0' cellspacing='0' style='padding-right:25px;' width='100%'>
                                                                                            <tr>
                                                                                                <td bgcolor='#FFFFFF' style='text-align:right;' width='100%'>
                                                                                                    <p><strong>Date :</strong> {{ dateFormat(date('Y-m-d')) }} </p>
                                                                                                    <!-- <a href='#'><img alt='Main banner image and link' border='0' src='http://placehold.it/72x100' style='display:inline-block; max-width:72px !important; width:100% !important; height:auto !important;'></a> -->
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr style="padding: 20px;">
                                                                                                <td bgcolor='#FFFFFF' style='text-align:center;' width='100%' id="subject"> </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                        <table bgcolor='#FFFFFF' border='0' cellpadding='25' cellspacing='0' width='100%'>
                                                                                            <tr>
                                                                                                <td bgcolor='#FFFFFF' style='text-align:left;' width='100%'>
                                                                                                    <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; padding:0; font-weight:normal;'>
                                                                                                        Dear {{ userName('Student', $student->id) }},
                                                                                                        <br>
                                                                                                        {{ fullAddress('Student', $student->id) }}
                                                                                                    </p>
                                                                                                    <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px;margin-left:20px; padding:0; font-weight:normal;' id="content"></p>

                                                                                                    <table border='0' cellpadding='0' cellspacing='0' class='emailwrapto100pc' width='100%'>
                                                                                                        <!-- <tr>
                                                                                                            <td align='right' class='emailcolsplit' valign='top' width='100%'>
                                                                                                                <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; margin-left:20px; padding:0; font-weight:normal; text-align:left;'>
                                                                                                                    Please call us at {{ $company->phone }} with any questions.
                                                                                                                </p>
                                                                                                            </td>
                                                                                                        </tr> -->
                                                                                                        <tr>
                                                                                                            <td align='left' class='emailcolsplit' valign='top' width='58%'>
                                                                                                                <p style='font-size:15px; margin: 0; line-height: 1.25em;'>
                                                                                                                <p>
                                                                                                                    <strong style='font-size: 20px'>Sincierly Yours</strong><br>
                                                                                                                    <p id="regards_name"></p>
                                                                                                                    <p id="regards_position"></p>
                                                                                                                    <!-- <abbr title='Phone'><strong>P:</strong></abbr>555.555.5555<br> -->
                                                                                                                    <!-- <strong>Email:</strong><a href='mailto:hr@yourcompany.com'>hr@yourcompany.com</a> -->
                                                                                                                </p>
                                                                                                                </p>
                                                                                                            </td>
                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                            <td>

                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </body>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
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
        $('#studentemail').validate({
            rules: {
                email_id: {
                    required: true,
                },
                email_template_id: {
                    required: true,
                },
            },
            messages: {
                email_id: "Please choose one email",
                email_template_id: "Please choose email template",
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

    $('#email_template_id').change(function() {
        var id = $(this).val();
        url = "{{url('admin/student/email/template/selected')}}?id=" + id,
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if (response != null) {
                        $('#email_subject').val(response.subject);
                        CKEDITOR.instances.email_content.setData(response.content);
                        $('#email_regards_name').val(response.regards_name);
                        $('#email_regards_position').val(response.regards_position);

                        var subjectWithPrefix = '<strong>Subject:</strong> ' + response.subject;

                        $('#subject').html(subjectWithPrefix);
                        console.log('select_subject', subjectWithPrefix);

                        $('#content').html(response.content);
                        console.log('select_content', response.content);

                        $('#regards_name').html(response.regards_name);
                        console.log('regards_name', response.regards_name);

                        $('#regards_position').html(response.regards_position);
                        console.log('regards_position', response.regards_position);
                    }
                }
            });
    });

    function showEmail() {
        document.getElementById('email_fields').style.display = "block";
        document.getElementById('edit_email').style.display = "none";
    }

    const emailSubject = document.getElementById('email_subject');
    const subject = document.getElementById('subject');

    emailSubject.addEventListener('input', function() {
        var subjectWithPrefix = '<strong>Subject:</strong> ' + emailSubject.value;
        $('#subject').html(subjectWithPrefix);
        console.log('typed_subject', subjectWithPrefix);
    });

    CKEDITOR.on('instanceReady', function(event) {
        var editor = event.editor;
        var contentElement = document.getElementById('content');

        editor.on('change', function() {
            console.log("Changes");
            contentElement.innerHTML = editor.getData();
        });
    });
</script>
@endsection