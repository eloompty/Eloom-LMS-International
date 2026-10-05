<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ getTitle() }} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset(getFavIcon()) }}" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/dist/css/adminlte.min.css') }}">
    <!-- Switch Button -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/dist/css/switch.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }} ">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Custom Button -->
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/AdminLTE/dist/css/add-custom.css') }}">
    @php $userTheme = Auth::guard('user')->user()->theme; @endphp
    @if ($userTheme == 'theme3')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap">
    <link rel="stylesheet" href="{{ asset('themes/theme3.css') }}">
    @endif
    <!-- Scripts for chats -->
    @yield('header-script')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed {{ $userTheme == 'dark' ? 'dark-mode layout-navbar-fixed' : '' }} {{ $userTheme == 'theme3' ? 'theme3-active' : '' }}">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset(getLogo()) }}" alt="AdminLTELogo" height="60" width="60">
        </div>
        <!-- Navbar -->
        @include('user::dashboard.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @if ($userTheme == 'theme2')
        @include('user::dashboard.sidebar2')
        @elseif ($userTheme == 'theme3')
        @include('user::dashboard.sidebar3')
        @else
        @include('user::dashboard.sidebar')
        @endif
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        @include('user::dashboard.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- Script to toggle theme -->
    <script>
        function themeSelector() {
            var checkBox = document.getElementById("themeSelector");
            var text = document.getElementById("themeText");
            var url = "{{ url('admin/theme')}}";
            if (checkBox.checked == true) {
                $.ajax({
                    url: url,
                    contentType: "application/json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        theme: 'dark',
                    },
                    dataType: "json",
                    type: 'GET',
                    success: function(response) {
                        window.location.reload();
                    }
                });
            } else {
                $.ajax({
                    url: url,
                    contentType: "application/json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        theme: 'light',
                    },
                    dataType: "json",
                    type: 'GET',
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
    <!-- jQuery -->
    <script src="{{ asset('themes/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('themes/AdminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('themes/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('themes/AdminLTE/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('themes/AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('themes/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('themes/AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="{{ asset('themes/AdminLTE/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('themes/AdminLTE/plugins/chart.js/Chart.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('themes/AdminLTE/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('themes/AdminLTE/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('themes/AdminLTE/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('themes/AdminLTE/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('themes/AdminLTE/dist/js/pages/dashboard2.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('themes/AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>
    @if (config('firebase.web.projectId'))
    <!-- Firebase -->
    <!-- <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase.js"></script> -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
    <script>
        const firebaseConfig = @json(array_filter(config('firebase.web')));
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function startFCM() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken({
                        vapidKey: @json(config('firebase.vapid_key'))
                    })
                })
                .then(function(token) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("admin.device.save") }}',
                        type: 'POST',
                        data: {
                            fcm_token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            // alert(response.message)
                            console.log('token:', token);
                            console.log('success', response.message);
                        },
                        error: function(error) {
                            // alert(error.error);
                            console.log('error:', error.error);
                        },
                    });
                }).catch(function(error) {
                    // alert(error);
                    console.log(error);
                });
        }
        startFCM();
        messaging.onMessage(function(payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script>
    @endif
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $("#example3").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 20,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
            $("#example4").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 50,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
        });
        $(document).ready(function() {
            $('textarea').on('click', function() {
                $(this).height(0);
                $(this).height(this.scrollHeight);
            });
        });
    </script>
    @yield('scripts')
</body>

</html>