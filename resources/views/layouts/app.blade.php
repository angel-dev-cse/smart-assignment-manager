<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-current-user-id="{{auth()->user()->id}}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Smart Assignment Management</title>

    <!-- Add the theme's CSS -->
    <link rel="stylesheet" href="{{ asset('startheme/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/js/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('startheme/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->

    <!-- Add the theme's favicon -->
    <link rel="shortcut icon" href="{{ asset('startheme/images/logo.ico') }}" />

    <!-- Add custom fonts (if needed) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('custom.css') }}">

    <!-- Add the CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- inject:js -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="{{ asset('startheme/js/off-canvas.js') }}"></script>
    <script src="{{ asset('startheme/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('startheme/js/template.js') }}"></script>
    <script src="{{ asset('startheme/js/settings.js') }}"></script>
    <script src="{{ asset('startheme/js/todolist.js') }}"></script>
    <script src="{{ asset('startheme/js/tablesorter.js') }}"></script>
    <script src="{{ asset('startheme/js/jq.tablesort.js') }}"></script>
    <script src="{{ asset('startheme/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('startheme/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('startheme/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('startheme/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('startheme/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('startheme/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('startheme/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('startheme/js/dashboard.js') }}"></script>
    <script src="{{ asset('startheme/js/Chart.roundedBarCharts.js') }}"></script>

    <!-- Google Viewer -->
    <script src="https://www.google.com/jsapi"></script>

    <!-- Add Vite scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Include the navigation bar -->
    <!-- @include('layouts.navigation') -->
    <div class="container-scroller">
        <x-navbar />
        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div class="alert alert-fill-success mx-20">
                    <i class="mdi mdi-12px mdi-information-outline"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-fill-danger">
                    <i class="mdi mdi-12px mdi-close-circle-outline"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-fill-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="mdi mdi-12px mdi-close-circle-outline"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <x-chat-popup/>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
