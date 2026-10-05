<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | LMS</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <!-- Bootstrap 4 + AdminLTE core -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/dist/css/alt/adminlte.core.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Register styles -->
    <link rel="stylesheet" href="{{ asset('themes/register.css') }}">
</head>

<body>
    <div class="reg-wrapper">
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="{{ asset('themes/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('themes/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
