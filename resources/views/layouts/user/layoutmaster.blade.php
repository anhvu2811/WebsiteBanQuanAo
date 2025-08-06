<!DOCTYPE html>
<html lang="vi">
<head>
    <title id="site-title">@yield('page_title')</title>
    <meta charset="utf-8">
</head>
<body>
    @include('layouts/user/header')
    @yield('content')
    @yield('scripts')
    @include('layouts/user/footer')
</body>
</html>
