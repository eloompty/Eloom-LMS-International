@extends('user::layouts.master')
@section('title', 'Admin | Email Settings')

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
                <h1>Email Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Email Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- form start -->
    <form id="updatesetting" action="{{ route('admin.setting.email.update') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Email Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="email_setting_type">Email Setting Type</label>
                                    <select name="email_setting_type" class="form-control" id="email_setting_type" onchange="emailSetting(this)">
                                        <option value="" selected disabled>-- Select Email Setting Type --</option>
                                        <!-- <option @if(getSettingValue('email_setting_type')=='google' ) selected @endif value="google">Google</option> -->
                                        <option @if(getSettingValue('email_setting_type')=='mailchimp' ) selected @endif value="mailchimp">Mailchimp</option>
                                        <option @if(getSettingValue('email_setting_type')=='office365' ) selected @endif value="office365">Office365</option>
                                        <option @if(getSettingValue('email_setting_type')=='mailgun' ) selected @endif value="mailgun">Mailgun</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="google" @if(getSettingValue('email_setting_type')=='google' ) @else style="display: none;" @endif>
                                <div class="form-group col-sm-4">
                                    <label for="google_client_id">Google Client Id</label>
                                    <textarea name="google_client_id" class="form-control" id="google_client_id" placeholder="Enter your-google-client-id">{{ getSettingValue('google_client_id') }}</textarea>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="google_client_secret">Google Client Id</label>
                                    <textarea name="google_client_secret" class="form-control" id="google_client_secret" placeholder="Enter your-google-client-secret">{{ getSettingValue('google_client_secret') }}</textarea>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="google_redirect_uri">Google Redirect URI</label>
                                    <textarea name="google_redirect_uri" class="form-control" id="google_redirect_uri" placeholder="Enter http://localhost:8000/callback">{{ getSettingValue('google_redirect_uri') }}</textarea>
                                </div>
                            </div>
                            <div class="row" id="mailchimp" @if(getSettingValue('email_setting_type')=='mailchimp' ) @else style="display: none;" @endif>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_host">Mail Host</label>
                                    <input type="text" name="mailchimp_mail_host" class="form-control" id="mailchimp_mail_host" placeholder="Enter MAIL_HOST" value="{{ getSettingValue('mailchimp_mail_host') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_port">Mail Port</label>
                                    <input type="text" name="mailchimp_mail_port" class="form-control" id="mailchimp_mail_port" placeholder="Enter MAIL_PORT" value="{{ getSettingValue('mailchimp_mail_port') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_username">Mail Username</label>
                                    <input type="text" name="mailchimp_mail_username" class="form-control" id="mailchimp_mail_username" placeholder="Enter MAIL_USERNAME" value="{{ getSettingValue('mailchimp_mail_username') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_password">Mail Password</label>
                                    <input type="text" name="mailchimp_mail_password" class="form-control" id="mailchimp_mail_password" placeholder="Enter MAIL_PASSWORD" value="{{ getSettingValue('mailchimp_mail_password') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_encryption">Mail Encryption</label>
                                    <input type="text" name="mailchimp_mail_encryption" class="form-control" id="mailchimp_mail_encryption" placeholder="Enter MAIL_ENCRYPTION" value="{{ getSettingValue('mailchimp_mail_encryption') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_from_address">Mail From Address</label>
                                    <input type="text" name="mailchimp_mail_from_address" class="form-control" id="mailchimp_mail_from_address" placeholder="Enter MAIL_FROM_ADDRESS" value="{{ getSettingValue('mailchimp_mail_from_address') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailchimp_mail_from_name">Mail From Name</label>
                                    <input type="text" name="mailchimp_mail_from_name" class="form-control" id="mailchimp_mail_from_name" placeholder="Enter MAIL_FROM_NAME" value="{{ getSettingValue('mailchimp_mail_from_name') }}">
                                </div>
                            </div>
                            <div class="row" id="office365" @if(getSettingValue('email_setting_type')=='office365' ) @else style="display: none;" @endif>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_host">Mail Host</label>
                                    <input type="text" name="office365_mail_host" class="form-control" id="office365_mail_host" placeholder="Enter MAIL_HOST" value="{{ getSettingValue('office365_mail_host') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_port">Mail Port</label>
                                    <input type="text" name="office365_mail_port" class="form-control" id="office365_mail_port" placeholder="Enter MAIL_PORT" value="{{ getSettingValue('office365_mail_port') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_username">Mail Username</label>
                                    <input type="text" name="office365_mail_username" class="form-control" id="office365_mail_username" placeholder="Enter MAIL_USERNAME" value="{{ getSettingValue('office365_mail_username') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_password">Mail Password</label>
                                    <input type="text" name="office365_mail_password" class="form-control" id="office365_mail_password" placeholder="Enter MAIL_PASSWORD" value="{{ getSettingValue('office365_mail_password') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_encryption">Mail Encryption</label>
                                    <input type="text" name="office365_mail_encryption" class="form-control" id="office365_mail_encryption" placeholder="Enter MAIL_ENCRYPTION" value="{{ getSettingValue('office365_mail_encryption') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_from_address">Mail From Address</label>
                                    <input type="text" name="office365_mail_from_address" class="form-control" id="office365_mail_from_address" placeholder="Enter MAIL_FROM_ADDRESS" value="{{ getSettingValue('office365_mail_from_address') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="office365_mail_from_name">Mail From Name</label>
                                    <input type="text" name="office365_mail_from_name" class="form-control" id="office365_mail_from_name" placeholder="Enter MAIL_FROM_NAME" value="{{ getSettingValue('office365_mail_from_name') }}">
                                </div>
                            </div>
                            <div class="row" id="mailgun" @if(getSettingValue('email_setting_type')=='mailgun' ) @else style="display: none;" @endif>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_host">Mail Host</label>
                                    <input type="text" name="mailgun_mail_host" class="form-control" id="mailgun_mail_host" placeholder="Enter MAIL_HOST" value="{{ getSettingValue('mailgun_mail_host') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_port">Mail Port</label>
                                    <input type="text" name="mailgun_mail_port" class="form-control" id="mailgun_mail_port" placeholder="Enter MAIL_PORT" value="{{ getSettingValue('mailgun_mail_port') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_username">Mail Username</label>
                                    <input type="text" name="mailgun_mail_username" class="form-control" id="mailgun_mail_username" placeholder="Enter MAIL_USERNAME" value="{{ getSettingValue('mailgun_mail_username') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_password">Mail Password</label>
                                    <input type="text" name="mailgun_mail_password" class="form-control" id="mailgun_mail_password" placeholder="Enter MAIL_PASSWORD" value="{{ getSettingValue('mailgun_mail_password') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_encryption">Mail Encryption</label>
                                    <input type="text" name="mailgun_mail_encryption" class="form-control" id="mailgun_mail_encryption" placeholder="Enter MAIL_ENCRYPTION" value="{{ getSettingValue('mailgun_mail_encryption') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_from_address">Mail From Address</label>
                                    <input type="text" name="mailgun_mail_from_address" class="form-control" id="mailgun_mail_from_address" placeholder="Enter MAIL_FROM_ADDRESS" value="{{ getSettingValue('mailgun_mail_from_address') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_mail_from_name">Mail From Name</label>
                                    <input type="text" name="mailgun_mail_from_name" class="form-control" id="mailgun_mail_from_name" placeholder="Enter MAIL_FROM_NAME" value="{{ getSettingValue('mailgun_mail_from_name') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_domain">Mailgun Domain</label>
                                    <input type="text" name="mailgun_domain" class="form-control" id="mailgun_domain" placeholder="Enter MAILGUN_DOMAIN" value="{{ getSettingValue('mailgun_domain') }}">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="mailgun_secret">Mailgun Secret</label>
                                    <input type="text" name="mailgun_secret" class="form-control" id="mailgun_secret" placeholder="Enter MAILGUN_SECRET" value="{{ getSettingValue('mailgun_secret') }}">
                                </div>
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
        $('#updatesetting').validate({
            rules: {
                email_setting_type: {
                    required: true,
                },
                google_client_id: {
                    required: true,
                },
                google_client_secret: {
                    required: true,
                },
                google_redirect_uri: {
                    required: true,
                },
                office365_mail_host: {
                    required: true,
                },
                office365_mail_port: {
                    required: true,
                },
                office365_mail_username: {
                    required: true,
                },
                office365_mail_password: {
                    required: true,
                },
                office365_mail_encryption: {
                    required: true,
                },
                office365_mail_from_address: {
                    required: true,
                },
                office365_mail_from_name: {
                    required: true,
                },
                mailgun_mail_host: {
                    required: true,
                },
                mailgun_mail_port: {
                    required: true,
                },
                mailgun_mail_username: {
                    required: true,
                },
                mailgun_mail_password: {
                    required: true,
                },
                mailgun_mail_encryption: {
                    required: true,
                },
                mailgun_mail_from_address: {
                    required: true,
                },
                mailgun_mail_from_name: {
                    required: true,
                },
                mailgun_domain: {
                    required: true,
                },
                mailgun_secret: {
                    required: true,
                },
                mailchimp_mail_host: {
                    required: true,
                },
                mailchimp_mail_port: {
                    required: true,
                },
                mailchimp_mail_username: {
                    required: true,
                },
                mailchimp_mail_password: {
                    required: true,
                },
                mailchimp_mail_encryption: {
                    required: true,
                },
                mailchimp_mail_from_address: {
                    required: true,
                },
                mailchimp_mail_from_name: {
                    required: true,
                },
            },
            messages: {
                email_setting_type: "Please choose email setting type",
                google_client_id: "Please enter google client id",
                google_client_secret: "Please enter google client secret",
                google_redirect_uri: "Please enter google redirect uri",
                mailchimp_api_key: "Please enter mailchimp api key",
                office365_mail_host: "Please enter office365 mail host",
                office365_mail_port: "Please enter office365 mail port",
                office365_mail_username: "Please enter office365 mail username",
                office365_mail_password: "Please enter office365 mail password",
                office365_mail_encryption: "Please enter office365 mail encryption",
                office365_mail_from_address: "Please enter office365 mail from address",
                office365_mail_from_name: "Please enter office365 mail from name",
                mailgun_mail_host: "Please enter mailgun mail host",
                mailgun_mail_port: "Please enter mailgun mail port",
                mailgun_mail_username: "Please enter mailgun mail username",
                mailgun_mail_password: "Please enter mailgun mail password",
                mailgun_mail_encryption: "Please enter mailgun mail encryption",
                mailgun_mail_from_address: "Please enter mailgun mail from address",
                mailgun_mail_from_name: "Please enter mailgun mail from name",
                mailgun_domain: "Please enter mailgun domain",
                mailgun_secret: "Please enter mailgun secret",
                mailchimp_mail_host: "Please enter mailchimp mail host",
                mailchimp_mail_port: "Please enter mailchimp mail port",
                mailchimp_mail_username: "Please enter mailchimp mail username",
                mailchimp_mail_password: "Please enter mailchimp mail password",
                mailchimp_mail_encryption: "Please enter mailchimp mail encryption",
                mailchimp_mail_from_address: "Please enter mailchimp mail from address",
                mailchimp_mail_from_name: "Please enter mailchimp mail from name",
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

    function emailSetting(that) {
        if (that.value == "google") {
            console.log("setting: ", "google");
            document.getElementById("google").style.display = "flex";
            document.getElementById("mailchimp").style.display = "none";
            document.getElementById("office365").style.display = "none";
            document.getElementById("mailgun").style.display = "none";
        } else if (that.value == "mailchimp") {
            document.getElementById("google").style.display = "none";
            document.getElementById("mailchimp").style.display = "flex";
            document.getElementById("office365").style.display = "none";
            document.getElementById("mailgun").style.display = "none";
        } else if (that.value == "office365") {
            document.getElementById("google").style.display = "none";
            document.getElementById("mailchimp").style.display = "none";
            document.getElementById("office365").style.display = "flex";
            document.getElementById("mailgun").style.display = "none";
        } else if (that.value == "mailgun") {
            document.getElementById("google").style.display = "none";
            document.getElementById("mailchimp").style.display = "none";
            document.getElementById("office365").style.display = "none";
            document.getElementById("mailgun").style.display = "flex";
        }
    }
</script>
@endsection