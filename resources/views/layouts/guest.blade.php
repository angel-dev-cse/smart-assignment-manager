<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Smart Assignment</title>

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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <!-- Add this to the head section of your layout or view -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" x-data>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <img src="{{ asset('startheme/images/logo.png') }}" style="height:8rem;margin:2rem" />
                </a>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            {{ $slot }}

        </div>
    </body>
</html>
