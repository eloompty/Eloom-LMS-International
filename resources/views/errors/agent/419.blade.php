@extends('errors.layout')
@section('title', '419 Error')
@section('content')
<h1>419 Error Page</h1>
<p class="zoom-area"><b>Session Expired,</b> Please login. </p>
<section class="error-container">
    <span>4</span>
    <span>1</span>
    <span>9</span>
</section>
<div class="link-container">
    <a href="{{ route('agent.login') }}" class="more-link">Go to login page</a>
</div>
@endsection