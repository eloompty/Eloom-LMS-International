@extends('user::layouts.email')
@section('title', 'Reset Password Email')

@section('content')

<h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">You have
    requested to reset your password</h1>
<span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
<p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
    We cannot simply send you your old password. A unique link to reset your
    password has been generated for you. To reset your password, click the
    following link and follow the instructions.
</p>
<a href="{{ $link }}" style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Reset
    Password
</a>

@endsection