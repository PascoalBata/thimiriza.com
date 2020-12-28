<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="Materialize is a modern responsive CSS framework based on Material Design by Google. ">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{asset ('assets/css/materialize-css/materialize.min.css')}}"
        media="screen,projection">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            height: 100%;
            background-image: url("{{asset('assets/images/main_bg.jpg')}}");
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
        }
        header{

        }
        main{
            position: relative;
        }

        footer {
            width: 100%;
            bottom: 0;
            position: absolute;
        }
    </style>
    @yield('style')
</head>
<body>
<header>

</header>
<main>
    @yield('content')
</main>
<footer class="page-footer grey lighten-2" style="padding-top: 0%;">
    @include('pt.Footer.footer')
</footer>
<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="{{asset ('assets/js/jquery/dist/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset ('assets/js/materialize-css/materialize.min.js')}}"></script>
@yield('script')
</body>
</html>
