<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title>{{ getTitle() }} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset(getFavIcon()) }}" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    
    @yield('content')

    <!-- jQuery -->
    <script src="{{ asset('themes/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('themes/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('themes/AdminLTE/dist/js/adminlte.min.js') }}"></script>
</body>

</html>