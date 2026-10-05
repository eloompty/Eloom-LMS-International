@extends('user::layouts.master')
@section('title', 'Admin')
@php $userTheme = Auth::guard('user')->user()->theme ?? 'theme2'; @endphp

@section('header-script')
@if ($userTheme == 'theme3')
<style>
    .profile-page { color: #111827; }
    .profile-page-title { margin: 0; color: #111827; font-size: 24px; font-weight: 800; line-height: 1.2; }
    .profile-page-subtitle { margin: 6px 0 0; color: #6b7280; font-size: 13px; }
    .profile-alert { border: 1px solid #bbf7d0; border-radius: 8px; background: #f0fdf4; color: #166534; box-shadow: 0 10px 24px rgba(22,101,52,.08); }
    .profile-shell-card { border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; box-shadow: 0 10px 28px rgba(15,23,42,.06); overflow: hidden; }
    .profile-identity { padding: 24px 18px; text-align: center; background: linear-gradient(135deg, #eff6ff 0%, #ffffff 56%, #f0fdfa 100%); border-bottom: 1px solid #e5e7eb; }
    .profile-avatar-wrap { position: relative; display: inline-flex; align-items: center; justify-content: center; width: 116px; height: 116px; margin-bottom: 14px; border-radius: 50%; background: #fff; box-shadow: 0 16px 32px rgba(15,23,42,.12); }
    .profile-avatar { width: 104px; height: 104px; border-radius: 50%; object-fit: cover; border: 3px solid #ffffff; }
    .profile-avatar-status { position: absolute; right: 9px; bottom: 11px; width: 16px; height: 16px; border: 3px solid #fff; border-radius: 50%; background: #10b981; }
    .profile-name { margin: 0; color: #0f172a; font-size: 18px; font-weight: 800; line-height: 1.3; overflow-wrap: anywhere; }
    .profile-role { display: inline-flex; align-items: center; gap: 7px; margin-top: 10px; padding: 6px 10px; border-radius: 999px; color: #1d4ed8; background: #dbeafe; font-size: 12px; font-weight: 800; }
    .profile-summary-list { padding: 16px; }
    .profile-summary-item { display: flex; align-items: flex-start; gap: 12px; padding: 13px 0; border-bottom: 1px solid #f1f5f9; }
    .profile-summary-item:last-child { border-bottom: 0; }
    .profile-summary-icon { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 34px; width: 34px; height: 34px; border-radius: 8px; color: #2563eb; background: #eff6ff; }
    .profile-summary-label { display: block; color: #64748b; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0; }
    .profile-summary-value { display: block; margin-top: 3px; color: #111827; font-size: 13px; font-weight: 600; line-height: 1.3; overflow-wrap: anywhere; }
    .profile-panel-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 20px; border-bottom: 1px solid #edf2f7; background: #fff; }
    .profile-panel-title { margin: 0; color: #111827; font-size: 16px; font-weight: 700; line-height: 1.25; }
    .profile-panel-subtitle { margin: 5px 0 0; color: #64748b; font-size: 12px; font-weight: 600; }
    .profile-panel-badge { display: inline-flex; align-items: center; gap: 7px; padding: 7px 11px; border-radius: 999px; color: #047857; background: #d1fae5; font-size: 12px; font-weight: 800; white-space: nowrap; }
    .profile-form-body { padding: 20px; }
    .profile-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .profile-field { margin-bottom: 0; }
    .profile-field.profile-field-wide { grid-column: 1 / -1; }
    .profile-field label { display: flex; align-items: center; gap: 7px; margin-bottom: 7px; color: #334155; font-size: 12px; font-weight: 800; }
    .profile-field label i { color: #2563eb; }
    .profile-field .form-control, .profile-field .custom-file-label { min-height: 42px; border-color: #dbe3ef; border-radius: 8px; color: #111827; font-size: 14px; box-shadow: none; }
    .profile-field .form-control:focus, .profile-field .custom-file-input:focus ~ .custom-file-label { border-color: #93c5fd; box-shadow: 0 0 0 .2rem rgba(37,99,235,.12); }
    .profile-field .custom-file-label { display: flex; align-items: center; color: #64748b; font-weight: 700; }
    .profile-field .custom-file-label::after { display: inline-flex; align-items: center; height: 40px; border-radius: 0 8px 8px 0; border-color: #dbe3ef; color: #1d4ed8; background: #eff6ff; font-weight: 800; }
    .profile-password-note { margin-top: 6px; color: #64748b; font-size: 12px; }
    .profile-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 18px; border-top: 1px solid #edf2f7; }
    .profile-submit-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 42px; padding: 10px 18px; border: 0; border-radius: 8px; color: #fff; background: #2563eb; font-size: 13px; font-weight: 800; box-shadow: 0 12px 24px rgba(37,99,235,.22); }
    .profile-submit-btn:hover { color: #fff; background: #1d4ed8; }
    @media (max-width: 991.98px) { .profile-form-grid { grid-template-columns: 1fr; } }
    @media (max-width: 575.98px) {
        .profile-page-title { font-size: 21px; }
        .profile-panel-header { align-items: flex-start; flex-direction: column; }
        .profile-form-body { padding: 16px; }
        .profile-actions { align-items: stretch; flex-direction: column; }
        .profile-submit-btn { width: 100%; }
    }
</style>
@endif
@endsection

@section('content')
@if ($userTheme != 'theme3')
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
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" id="box-image" src="{{ asset(Auth::guard('user')->user()->image) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::guard('user')->user()->first_name }} {{ Auth::guard('user')->user()->family_name }}</h3>

                        <p class="text-muted text-center">{{ Auth::guard('user')->user()->userType->name }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Country</b> <a class="float-right">{{ Auth::guard('user')->user()->country->name }}</a>
                            </li>
                        </ul>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">{{ Auth::guard('user')->user()->email }}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
                        <p class="text-muted">{{ Auth::guard('user')->user()->phone }}</p>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Update Details</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="settings">
                                <form class="form-horizontal" id="user" method="POST" action="{{ route('admin.user.update', Auth::guard('user')->user()->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ Auth::guard('user')->user()->first_name }}" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="family_name" class="col-sm-2 col-form-label">Family Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="family_name" name="family_name" value="{{ Auth::guard('user')->user()->family_name }}" placeholder="Family Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::guard('user')->user()->email }}" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::guard('user')->user()->phone }}" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="zip_code" class="col-sm-2 col-form-label">Country</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="country_id">
                                                <option value="" selected disabled>-- Select Country --</option>
                                                @foreach ($countries as $key => $value)
                                                <option value="{{ $key }}" @if ($key==Auth::guard('user')->user()->country_id) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-sm-2 col-form-label">Image</label>
                                        <div class="col-sm-10">
                                            <div class="custom-file">
                                                <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@else
{{-- ===== THEME 3: Enterprise Profile Layout ===== --}}
@php $adminUser = Auth::guard('user')->user(); @endphp
<div class="profile-page">
    @if ($text = Session::get('success'))
    <div class="container-fluid pt-3">
        <div class="alert profile-alert alert-dismissible fade show mb-0">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $text }}</strong>
        </div>
    </div>
    @endif

    <section class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-7">
                    <h1 class="profile-page-title">Profile</h1>
                    <p class="profile-page-subtitle">Manage your administrator identity, contact information, and account photo.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-xl-3 mb-3">
                    <div class="profile-shell-card mb-3">
                        <div class="profile-identity">
                            <span class="profile-avatar-wrap">
                                <img class="profile-avatar" id="box-image" src="{{ asset($adminUser->image) }}" alt="User profile picture">
                                <span class="profile-avatar-status"></span>
                            </span>
                            <h3 class="profile-name">{{ $adminUser->first_name }} {{ $adminUser->family_name }}</h3>
                            <span class="profile-role"><i class="fas fa-shield-alt"></i> {{ optional($adminUser->userType)->name }}</span>
                        </div>
                        <div class="profile-summary-list">
                            <div class="profile-summary-item">
                                <span class="profile-summary-icon"><i class="fas fa-globe-asia"></i></span>
                                <span>
                                    <span class="profile-summary-label">Country</span>
                                    <span class="profile-summary-value">{{ optional($adminUser->country)->name ?: 'Not set' }}</span>
                                </span>
                            </div>
                            <div class="profile-summary-item">
                                <span class="profile-summary-icon"><i class="fas fa-envelope"></i></span>
                                <span>
                                    <span class="profile-summary-label">Email</span>
                                    <span class="profile-summary-value">{{ $adminUser->email }}</span>
                                </span>
                            </div>
                            <div class="profile-summary-item">
                                <span class="profile-summary-icon"><i class="fas fa-phone-alt"></i></span>
                                <span>
                                    <span class="profile-summary-label">Phone</span>
                                    <span class="profile-summary-value">{{ $adminUser->phone ?: 'Not set' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-xl-9 mb-3">
                    <div class="profile-shell-card">
                        <div class="profile-panel-header">
                            <div>
                                <h3 class="profile-panel-title">Update Details</h3>
                                <p class="profile-panel-subtitle">Keep your profile accurate for account recovery, notifications, and system audit records.</p>
                            </div>
                            <span class="profile-panel-badge"><i class="fas fa-lock"></i> Secure account</span>
                        </div>
                        <div class="profile-form-body">
                            <form id="user" method="POST" action="{{ route('admin.user.update', $adminUser->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="profile-form-grid">
                                    <div class="form-group profile-field">
                                        <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $adminUser->first_name }}" placeholder="First Name">
                                    </div>
                                    <div class="form-group profile-field">
                                        <label for="family_name"><i class="fas fa-user"></i> Family Name</label>
                                        <input type="text" class="form-control" id="family_name" name="family_name" value="{{ $adminUser->family_name }}" placeholder="Family Name">
                                    </div>
                                    <div class="form-group profile-field">
                                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $adminUser->email }}" placeholder="Email">
                                    </div>
                                    <div class="form-group profile-field">
                                        <label for="phone"><i class="fas fa-phone-alt"></i> Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $adminUser->phone }}" placeholder="Phone">
                                    </div>
                                    <div class="form-group profile-field">
                                        <label for="password"><i class="fas fa-key"></i> Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        <div class="profile-password-note">Leave blank to keep your current password.</div>
                                    </div>
                                    <div class="form-group profile-field">
                                        <label for="country_id"><i class="fas fa-globe-asia"></i> Country</label>
                                        <select class="form-control" id="country_id" name="country_id">
                                            <option value="" selected disabled>-- Select Country --</option>
                                            @foreach ($countries as $key => $value)
                                            <option value="{{ $key }}" @if ($key == $adminUser->country_id) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group profile-field profile-field-wide">
                                        <label for="image"><i class="fas fa-image"></i> Profile Image</label>
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="image" onchange="readURL(this);">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-actions">
                                    <button type="submit" class="profile-submit-btn"><i class="fas fa-save"></i> Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endif
@endsection

@section('scripts')
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#user').validate({
            rules: {
                first_name: { required: true },
                family_name: { required: true },
                email: { required: true },
                phone: { required: true, minlength: 7 },
            },
            messages: {
                first_name: "Please enter first name",
                family_name: "Please enter family name",
                email: "Please enter Email",
                phone: { required: "Please enter phone number", minlength: "Your phone number must be at least 7 characters long" },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) { $(element).addClass('is-invalid'); },
            unhighlight: function(element, errorClass, validClass) { $(element).removeClass('is-invalid'); }
        });

        $('#image').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass('selected').html(fileName || 'Choose file');
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#box-image').attr('src', e.target.result).width(104).height(104);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
