@extends('user::layouts.auth')
@section('title', 'Agent | Forgot Password')

@section('content')
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>LMS - Agent</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Enter your email to reset password</p>

            <form action="{{ route('agent.password.reset') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @if ($text = Session::get('error'))
                <p style="color:red;">{{ $text }}</p>
                @endif
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Send</button>
                    </div>
                    <!-- /.col -->
                    <div class="col-12">
                        @if ($text = Session::get('success'))
                        <p style="color:green;">{{ $text }}</p>
                        @endif
                    </div>
                </div>
            </form>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
@endsection