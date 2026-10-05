@extends('user::layouts.master')
@section('title', 'Admin | Student Emails')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Emails</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item active">Emails</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of {{ userName('Student', $student->id) }}'s Emails</h3>
                        <div class="col-md-12 text-right"><a href="{{ route('admin.student.email.create', $student->id) }}" class="btn btn-success">Send Email</a></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(count($emails) > 0)
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Template</th>
                                    <th>Sent By</th>
                                    <th>Sent Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($emails as $index => $value)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $value->email->name }}</td>
                                    <td>{{ $value->emailTemplate->name }}</td>
                                    <td>
                                        @if ($value->sender_type == 'Admin') {{ userName('Admin', $value->sender_id) }} (Admin) @else {{ userName('Trainer', $value->sender_id) }} (Trainer) @endif
                                    </td>
                                    <td data-sort='{{ convertDate($value->created_at) }}'>{{ dateFormat($value->created_at) }}</td>
                                    <td>
                                        <div class="form-group preview">
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-xl{{ $value->id }}">
                                                Preview Email
                                            </button>
                                            <div class="modal fade" id="modal-xl{{ $value->id }}">
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
                                                                                                                <p><strong>Date :</strong> {{ dateFormat($value->created_at) }} </p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr style="padding: 20px;">
                                                                                                            <td bgcolor='#FFFFFF' style='text-align:center;' width='100%' id="subject"> <strong>Subject: </strong> {{ $value->email_subject }}</td> </td>
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
                                                                                                                <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px;margin-left:20px; padding:0; font-weight:normal;' id="content"> <?php echo $value->email_content; ?></p>

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
                                                                                                                                @if ($value->email_regards_name != NULL) {{ $value->email_regards_name }} @else {{ $company->company_ceo }} @endif<br>
                                                                                                                                @if ($value->email_regards_position != NULL) {{ $value->email_regards_position }} @else {{ fullAddress('company', $company->id) }} @endif<br>
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Template</th>
                                    <th>Sent By</th>
                                    <th>Sent Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <h3>No Data Found</h3>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection