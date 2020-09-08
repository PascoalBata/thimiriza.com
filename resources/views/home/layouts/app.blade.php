<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/materialize-css/materialize.min.css') }}"
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
            background-image: url("{{ asset('assets/images/main_bg.jpg') }}");
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        header {}

        main {
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
        <ul style="width: 200px; height:100%;" id="slide-out" class="sidenav sidenav-fixed grey lighten-5">
            <li>
                <div style="padding-top: 48px; padding-bottom: 0px; height: 115px" class="user-view">
                    <div class="background" style="height: 115px;">
                        <img src="@yield('logo')">
                    </div>
                    <a href="#"><span class="white-text name">@yield('username')</span></a>
                    <a href="#"><span style="padding-bottom: 0px;"
                            class="white-text email">@yield('user_email')</span></a>
                </div>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/sales');" href="">
                    {{ __('Vendas') }} <i class="small material-icons">add_shopping_cart</i>
                </a>

            </li>

            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/clients_enterprise');" href="">
                    {{ __('Clientes') }} <i class="small material-icons">supervisor_account</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/products');" href="">
                    {{ __('Produtos') }} <i class="small material-icons">add_shopping_cart</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/services');" href="">
                    {{ __('Serviços') }} <i class="small material-icons">add_shopping_cart</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/services');" href="">
                    {{ __('Débitos') }} <i class="small material-icons">shopping_basket</i>

                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/services');" href="">
                    {{ __('Relatório') }} <i class="small material-icons">shopping_basket</i>

                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/users');" href="">
                    {{ __('Utilizadores') }} <i class="small material-icons">add_shopping_cart</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/company');" href="">
                    {{ __('Empresa') }} <i class="small material-icons">shopping_basket</i>

                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/services');" href="">
                    {{ __('Sobre Nós') }} <i class="small material-icons">shopping_basket</i>

                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                    onclick="window.history.replaceState(null, 'Thimiriza', '/');" href="">
                    {{ __('Sair') }} <i class="small material-icons">shopping_basket</i>

                </a>
            </li>
        </ul>
    </header>
    <main>
        <div id="app" class="row main left-align">
            <div class="col s12 m12 l8 xl8 push-l2 pull-l1 push-xl2 pull-xl1 container grey lighten-5">
                @yield('content')
            </div>
        </div>
    </main>
    <footer class="page-footer grey lighten-2" style="padding-top: 0%; transform: translateY(0%);">
        @include('pt.Footer.footer')
    </footer>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('assets/js/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/materialize-css/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/home.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('select').formSelect();
        });

        $(document).ready(function() {
            $('.sidenav').sidenav();
        });
        $(document).ready(function() {
            $('.modal').modal();
        });
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
