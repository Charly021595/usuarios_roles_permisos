<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.4.0-web/css/all.min.css') }}">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->

    <!-- MDB icon -->
    <link rel="icon" href="{{ asset('img/icon2.png') }}" type="image/x-icon" />  

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body class="login-page body_login">
    <div id="app">
        @yield('content')
    </div>

    <!-- /.login-box -->
    <footer class="footer_login">
        <img loading="lazy" src="{{ asset('img/TipsAnonimos.jpg') }}" style="width: 100%;">
    </footer>
</body>
</html>
