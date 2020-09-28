<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="{{asset ('assets/css/materialize-css/materialize.min.css')}}"
    media="screen,projection">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Thimiriza') }}</title>
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
            position: fixed;
        }
    </style>
    @yield('style')
</head>
<body>
    <header>

    </header>
    <main>
        <div id="app">
            @yield('content')
        </div>
    </main>
    <footer class="page-footer grey lighten-2" style="padding-top: 0%; transform: translateY(0%);">
        @include('pt.Footer.footer')
    </footer>

    <!-- Scripts -->
    <script type="text/javascript" src="{{asset ('assets/js/jquery/dist/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset ('assets/js/materialize-css/materialize.min.js')}}"></script>
    @yield('script')
</body>
</html>
