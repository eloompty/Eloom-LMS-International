@extends('user::layouts.master')
@section('title', 'Admin | Settings')

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
                <h1>Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="updatesetting" action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" value="{{ getSettingValue('title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="date_format">Date Format</label>
                                        <select name="date_format" class="form-control" id="date_format">
                                            <option value="" selected disabled>-- Select Date Format --</option>
                                            <option @if(getSettingValue('date_format')=='Y-m-d' )selected @endif value="Y-m-d">{{ date('Y-m-d') }} (yyyy-mm-dd)</option>
                                            <option @if(getSettingValue('date_format')=='d/m/Y' )selected @endif value="d/m/Y">{{ date('d/m/Y') }} (dd/mm/yyyy)</option>
                                            <option @if(getSettingValue('date_format')=='m/d/Y' )selected @endif value="m/d/Y">{{ date('m/d/Y') }} (mm/dd/yyyy)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="time_format">Time Format</label>
                                        <select name="time_format" class="form-control" id="time_format">
                                            <option value="" selected disabled>-- Select Time Format--</option>
                                            <option @if(getSettingValue('time_format')=='h:i A' )selected @endif value="h:i A">{{ date('h:i A') }}</option>
                                            <option @if(getSettingValue('time_format')=='h:i a' )selected @endif value="h:i a">{{ date('h:i a') }}</option>
                                            <option @if(getSettingValue('time_format')=='h:i:s' )selected @endif value="h:i:s">{{ date('h:i:s') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="address_format">Address Format</label>
                                        <select name="address_format" class="form-control" id="address_format">
                                            @foreach(addressFormatOptions() as $key => $label)
                                            <option @if(currentAddressFormat()==$key) selected @endif value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="student_id_field">Student ID Field</label>
                                        <select name="student_id_field" class="form-control" id="student_id_field">
                                            @foreach(studentIdFieldOptions() as $key => $label)
                                            <option @if(studentIdSettings()['field']==$key) selected @endif value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="student_id_automatic_fields">
                                        <div class="form-group">
                                            <label for="student_id_number_start">Student ID Number Start</label>
                                            <input type="number" name="student_id_number_start" class="form-control" id="student_id_number_start" min="1" value="{{ studentIdSettings()['number_start'] }}" placeholder="Enter Student ID Number Start">
                                        </div>
                                        <div class="form-group">
                                            <label for="student_id_format">Student ID Format</label>
                                            <select name="student_id_format" class="form-control" id="student_id_format">
                                                @foreach(studentIdFormatOptions() as $key => $label)
                                                <option @if(studentIdSettings()['format']==$key) selected @endif value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id="student_id_prefix_group">
                                            <label for="student_id_prefix">Student ID Prefix</label>
                                            <input type="text" name="student_id_prefix" class="form-control" id="student_id_prefix" value="{{ studentIdSettings()['prefix'] }}" placeholder="Enter Student ID Prefix">
                                        </div>
                                        <div class="form-group" id="student_id_suffix_group">
                                            <label for="student_id_suffix">Student ID Suffix</label>
                                            <input type="text" name="student_id_suffix" class="form-control" id="student_id_suffix" value="{{ studentIdSettings()['suffix'] }}" placeholder="Enter Student ID Suffix">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="teacher_id_field">Teacher ID Field</label>
                                        <select name="teacher_id_field" class="form-control" id="teacher_id_field">
                                            @foreach(teacherIdFieldOptions() as $key => $label)
                                            <option @if(teacherIdSettings()['field']==$key) selected @endif value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="teacher_id_automatic_fields">
                                        <div class="form-group">
                                            <label for="teacher_id_number_start">Teacher ID Number Start</label>
                                            <input type="number" name="teacher_id_number_start" class="form-control" id="teacher_id_number_start" min="1" value="{{ teacherIdSettings()['number_start'] }}" placeholder="Enter Teacher ID Number Start">
                                        </div>
                                        <div class="form-group">
                                            <label for="teacher_id_format">Teacher ID Format</label>
                                            <select name="teacher_id_format" class="form-control" id="teacher_id_format">
                                                @foreach(teacherIdFormatOptions() as $key => $label)
                                                <option @if(teacherIdSettings()['format']==$key) selected @endif value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id="teacher_id_prefix_group">
                                            <label for="teacher_id_prefix">Teacher ID Prefix</label>
                                            <input type="text" name="teacher_id_prefix" class="form-control" id="teacher_id_prefix" value="{{ teacherIdSettings()['prefix'] }}" placeholder="Enter Teacher ID Prefix">
                                        </div>
                                        <div class="form-group" id="teacher_id_suffix_group">
                                            <label for="teacher_id_suffix">Teacher ID Suffix</label>
                                            <input type="text" name="teacher_id_suffix" class="form-control" id="teacher_id_suffix" value="{{ teacherIdSettings()['suffix'] }}" placeholder="Enter Teacher ID Suffix">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="teaching_system">Teaching System</label>
                                        <select name="teaching_system" class="form-control" id="teaching_system">
                                            <option value="" selected disabled>-- Select Teaching System--</option>
                                            <option @if(getSettingValue('teaching_system')=='Subject' )selected @endif value="Subject">Subject</option>
                                            <option @if(getSettingValue('teaching_system')=='Unit' )selected @endif value="Unit">Unit</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="enable_student_assignment_ai_bot_checking">Enable Student Assignment AI Bot Checking</label>
                                        <select name="enable_student_assignment_ai_bot_checking" class="form-control" id="enable_student_assignment_ai_bot_checking">
                                            <option @if(getSettingValue('enable_student_assignment_ai_bot_checking')!='disable') selected @endif value="enable">Enable</option>
                                            <option @if(getSettingValue('enable_student_assignment_ai_bot_checking')=='disable') selected @endif value="disable">Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="enable_student_assignment_paraphrasing">Enable Student Assignment Paraphrasing</label>
                                        <select name="enable_student_assignment_paraphrasing" class="form-control" id="enable_student_assignment_paraphrasing">
                                            <option @if(getSettingValue('enable_student_assignment_paraphrasing')!='disable') selected @endif value="enable">Enable</option>
                                            <option @if(getSettingValue('enable_student_assignment_paraphrasing')=='disable') selected @endif value="disable">Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="enable_student_assignment_plagiarism_test">Enable Student Assignment Plagiarism Test</label>
                                        <select name="enable_student_assignment_plagiarism_test" class="form-control" id="enable_student_assignment_plagiarism_test">
                                            <option @if(getSettingValue('enable_student_assignment_plagiarism_test')!='disable') selected @endif value="enable">Enable</option>
                                            <option @if(getSettingValue('enable_student_assignment_plagiarism_test')=='disable') selected @endif value="disable">Disable</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fcm_server_api_key">FCM Server API Key</label>
                                        <textarea name="fcm_server_api_key" class="form-control" id="fcm_server_api_key" placeholder="Enter FCM Server API Key">{{ getSettingValue('fcm_server_api_key') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="fcm_sender_id">FCM Sender ID</label>
                                        <textarea name="fcm_sender_id" class="form-control" id="fcm_sender_id" placeholder="Enter FCM Sender ID">{{ getSettingValue('fcm_sender_id') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="logo" class="custom-file-input" id="logo" onchange="readURL(this);">
                                                <label class="custom-file-label" for="logo">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if (getSettingValue('logo'))
                                        <img src="{{ asset(getSettingValue('logo')) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @else
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="fav_icon">Fav Icon</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="fav_icon" class="custom-file-input" id="fav_icon" onchange="readFavURL(this);">
                                                <label class="custom-file-label" for="fav_icon">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if (getSettingValue('fav_icon'))
                                        <img src="{{ asset(getSettingValue('fav_icon')) }}" id="fav-box-image" alt="" style="width: 56px; border: #ebebeb 1px solid;">
                                        @else
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="fav-box-image" alt="" style="width: 56px; border: #ebebeb 1px solid;">
                                        @endif
                                    </div>
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
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readFavURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#fav-box-image')
                    .attr('src', e.target.result)
                    .width(56)
                    .height(56);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleStudentIdSettings() {
        var field = $('#student_id_field').val();
        var format = $('#student_id_format').val();
        var isAutomatic = field === 'automatic';

        $('#student_id_automatic_fields').toggle(isAutomatic);
        $('#student_id_prefix_group').toggle(isAutomatic && (format === 'prefix' || format === 'both'));
        $('#student_id_suffix_group').toggle(isAutomatic && (format === 'suffix' || format === 'both'));
    }

    $('#student_id_field, #student_id_format').on('change', toggleStudentIdSettings);
    toggleStudentIdSettings();

    function toggleTeacherIdSettings() {
        var field = $('#teacher_id_field').val();
        var format = $('#teacher_id_format').val();
        var isAutomatic = field === 'automatic';

        $('#teacher_id_automatic_fields').toggle(isAutomatic);
        $('#teacher_id_prefix_group').toggle(isAutomatic && (format === 'prefix' || format === 'both'));
        $('#teacher_id_suffix_group').toggle(isAutomatic && (format === 'suffix' || format === 'both'));
    }

    $('#teacher_id_field, #teacher_id_format').on('change', toggleTeacherIdSettings);
    toggleTeacherIdSettings();
</script>
@endsection
