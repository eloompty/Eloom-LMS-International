@extends('user::layouts.auth')
@section('title', 'Student | Reset Password')

@section('content')
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="../../index2.html" class="h1"><b>LMS - Student</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Enter new password</p>

            <form action="{{ route('student.password.update') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="hidden" class="form-control" value="{{ $email }}" name="email" required>
                </div>
                <input type="hidden" class="form-control" value="{{ $code }}" name="code">
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm-password" id="txtConfirmPassword" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @if ($text = Session::get('error'))
                <p style="color:red;">{{ $text }}</p>
                @endif
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Reset</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
@endsection