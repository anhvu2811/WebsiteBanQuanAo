<!DOCTYPE html>
<html lang="vi">
<head>
    <title id="site-title">@yield('page_title')</title>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        #toast-container.toast-top-right {
            top: 60px;
            right: 12px;
        }
    </style>
</head>
<body>
    @include('layouts/user/header')
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "3000",
            "positionClass": "toast-top-right"
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>
    @yield('content')
    @yield('scripts')
    @include('layouts/user/footer')
</body>
</html>
