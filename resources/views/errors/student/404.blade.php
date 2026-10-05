@extends('errors.layout')
@section('title', '404 Error')
@section('content')
<h1>404 Error Page</h1>
<p class="zoom-area"><b>Sorry,</b> the page you are looking for is unavailable. </p>
<section class="error-container">
    <span class="four"><span class="screen-reader-text">4</span></span>
    <span class="zero"><span class="screen-reader-text">0</span></span>
    <span class="four"><span class="screen-reader-text">4</span></span>
</section>
<div class="link-container">
    <a href="{{ route('student.dashboard') }}" class="more-link">Go to home page</a>
</div>
@endsection