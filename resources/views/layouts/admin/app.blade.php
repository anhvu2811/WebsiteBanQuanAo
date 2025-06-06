<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @stack('styles')
    @yield('head')
</head>
<body>
    <!-- ✅ Loading overlay -->
     {{-- <div id="loading-overlay" style=" position: fixed; z-index: 9999;background: white;width: 100%;height: 100%;top: 0;left: 0;    display: flex;align-items: center;justify-content: center;">
        <img src="{{ asset('images/animation_loading.gif') }}" alt="Loading..." style="width: 100px; height: auto;">
    </div> --}}

    @include('layouts.admin.sidebar')
    <header id="header">
        @include('layouts.admin.header')
    </header>
    <main id="mainContent">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ✅ Loading script -->
    {{-- <script>
        window.addEventListener('load', function () {
            setTimeout(() => {
                document.getElementById('loading-overlay').style.display = 'none';
                document.getElementById('mainContent').style.opacity = 1;
            }, 1000); // bạn có thể chỉnh thời gian delay
        });
    </script>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style> --}}
    @stack('scripts')
</body>
</html>
