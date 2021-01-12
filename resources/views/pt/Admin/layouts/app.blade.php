<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/thimiriza.png') }}">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/materialize-css/materialize.min.css') }}"
        media="screen,projection">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
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
            /*
            background-image: url("{{ asset('assets/images/main_bg.jpg') }}");
            */
            background-color: #fafafa;
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        header {}

        main {
            position: relative;
        }



        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        @media (min-width: 993px) {
            #float-btn {
                display: none;
            }
        }
        footer {
            z-index: 999 !important;
            width: 100%;
            bottom: 0;
            position: absolute;
            transform: translateY(0%);
        }
        .nav-link:hover{
            background-color:whitesmoke !important;
        }



    </style>
    @yield('style')
</head>

<body>
    <main style="margin-bottom: 2%;">


        <div class="row">

            <div class="col s12 m4 l2">
                <!-- Note that "m4 l3" was added -->
                <!-- Grey navigation panel

                This content will be:
                3-columns-wide on large screens,
                4-columns-wide on medium screens,
                12-columns-wide on small screens  -->
                <ul style="width: 250px; height:100%;" id="slide-out" class="sidenav sidenav-fixed grey lighten-2">
                    <li>
                        <div style="padding-top: 48px; padding-bottom: 0px; height: 115px" class="user-view">
                            <div class="background" style="height: 115px;">
                                <img style="height:100%; width:100%;" src="{{asset('assets/images/thimiriza.png')}}">
                            </div>
                            <a href="#"><span class="back-text name">@yield('username')</span></a>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link black-text sidenav-close" href="{{ route('show_companies') }}">
                            {{ __('Empresas') }} <i class="small material-icons">assignment</i>
                        </a>
                    </li>
                    <form method="POST" id="loggoutForm" action="{{ route('end_session') }}">
                        <li>
                            @csrf
                            @method('POST')
                            <a class="nav-link black-text sidenav-close" id="submit_btn" href="#">
                                {{ __('Sair') }} <i class="small material-icons">power_settings_new</i>
                            </a>
                        </li>
                    </form>
                </ul>
                <div id="float-btn" class="fixed-action-btn" style="z-index: 9999 !important;">
                    <a hre="#" data-target="slide-out" class="btn-floating btn-large teal lighten-2 sidenav-trigger">
                        <i class="large material-icons">menu</i>
                    </a>
                </div>

            </div>



            <div class="col s12 m12 l10">
                <!-- Note that "m8 l9" was added -->
                <!-- Teal page content

              This content will be:
          9-columns-wide on large screens,
          8-columns-wide on medium screens,
          12-columns-wide on small screens  -->
                <div id="app" class="row main left-align">
                    <div id="content" class="container grey lighten-5">
                        @yield('content')
                    </div>
                </div>
            </div>

        </div>

    </main>
    <footer class="page-footer grey lighten-2"
    style="padding-top: 0%;">
        @include('pt.Footer.footer')
    </footer>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('assets/js/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/materialize-css/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/home.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('select').formSelect();
            $('.dropdown-trigger').dropdown();
            $('.modal').modal();
            $('.sidenav').sidenav();
        });

        document.getElementById("submit_btn").onclick = function() {
            document.getElementById("loggoutForm").submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.fixed-action-btn');
            var instances = M.FloatingActionButton.init(elems, {
                direction: 'top'
            });
        });

    </script>
    @yield('script')
</body>
</html>
