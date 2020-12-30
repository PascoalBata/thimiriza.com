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

        @media (min-width: 1540px) {
            #content {
                width: 100%;
            }
        }

        @media (max-width: 992px) {
            #content {}
        }

        @media (min-width: 992px) {
            #content {
                width: 78%;
                margin-left: 3.8%;
            }

            .footer-copyright {
                margin-left: 7%;
            }
        }

        @media (min-width: 1050px) {
            #content {
                width: 80%;
                margin-left: 2.5%;
            }
        }

        @media (min-width: 1100px) {
            #content {
                width: 80%;
                margin-left: 2%;
            }
        }

        @media (min-width: 1200px) {
            #content {
                width: 80%;
                margin-left: 1%;
            }
        }

        @media (min-width: 1300px) {
            #content {
                width: 80%;
                margin-left: 0.1%;
            }
        }

        @media (min-width: 1800px) {
            #content {
                width: 100%;
                margin-left: 3%;
            }
        }

    </style>
    @yield('style')
</head>

<body>
    <header>
        <ul style="width: 250px; height:100%;" id="slide-out" class="sidenav sidenav-fixed grey lighten-5">
            <li>
                <div style="padding-top: 48px; padding-bottom: 0px; height: 115px" class="user-view">
                    <div class="background" style="height: 115px;">
                        <img style="height:100%; width:100%;" src="@yield('logo')">
                    </div>
                    <a href="#"><span class="white-text name">@yield('username')</span></a>
                    <a href="#"><span style="padding-bottom: 0px;"
                            class="white-text email">@yield('user_email')</span></a>
                </div>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close dropdown-trigger" href="#" data-target='dropdown1'>
                    {{ __('Vendas') }} <i class="small material-icons">local_grocery_store</i>
                </a>
                <ul id='dropdown1' class='dropdown-content'>
                    <li>
                        <a href="#">
                            {{ __('Vendas') }} <i class="small material-icons">local_grocery_store</i>
                        </a>
                    </li>
                    <li class="divider" tabindex="-1"></li>
                    <li>
                        <a href="{{route('view_sale')}}">
                            {{ __('Criar') }} <i class="small material-icons">add_shopping_cart</i>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('view_invoice_note')}}">
                            {{ __('Notas') }} <i class="small material-icons">note</i>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('view_credit')}}">
                            {{ __('Pagas') }} <i class="small material-icons">credit_card</i>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('view_debit')}}">
                            {{ __('Não pagas') }} <i class="small material-icons">event_busy</i>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close dropdown-trigger" href="#" data-target='dropdown3'>
                    {{ __('Produtos/Serviços') }} <i class="small material-icons">shop_two</i>
                </a>
                <ul id='dropdown3' class='dropdown-content'>
                    <li>
                        <a href="#">
                            {{ __('Produtos/Serviços') }} <i class="small material-icons">shop_two</i>
                        </a>
                    </li>
                    <li class="divider" tabindex="-1"></li>
                    <li>
                        <a class="nav-link black-text sidenav-close"
                        href="{{route('view_product')}}">
                            {{ __('Produtos') }} <i class="small material-icons">shopping_basket</i>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link black-text sidenav-close"
                        href="{{route('view_service')}}">
                            {{ __('Serviços') }} <i class="small material-icons">shop</i>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close dropdown-trigger" href="#" data-target='dropdown2'>
                    {{ __('Clientes') }} <i class="small material-icons">supervisor_account</i>
                </a>
                <ul id='dropdown2' class='dropdown-content'>
                    <li>
                        <a href="#">
                            {{ __('Clientes') }} <i class="small material-icons">supervisor_account</i>
                        </a>
                    </li>
                    <li class="divider" tabindex="-1"></li>
                    <li>
                        <a class="nav-link black-text sidenav-close"
                        href="{{route('view_client_singular')}}">
                            {{ __('Singulares') }} <i class="small material-icons">person</i>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link black-text sidenav-close"
                        href="{{route('view_client_enterprise')}}">
                            {{ __('Empresariais') }} <i class="small material-icons">business_center</i>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                href="{{route('view_report')}}">
                    {{ __('Relatórios') }} <i class="small material-icons">assignment</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                href="{{route('view_user')}}">
                    {{ __('Utilizadores') }} <i class="small material-icons">group_add</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                href="{{route('view_company')}}">
                    {{ __('Empresa') }} <i class="small material-icons">account_balance</i>
                </a>
            </li>
            <li>
                <a class="nav-link black-text sidenav-close"
                href="{{route('view_about')}}">
                    {{ __('Sobre Nós') }} <i class="small material-icons">work</i>
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
        <div id="float-btn" class="fixed-action-btn">
            <a hre="#" data-target="slide-out" class="btn-floating btn-large teal lighten-2 sidenav-trigger">
                <i class="large material-icons">menu</i>
            </a>
        </div>
    </header>
    <main style="margin-bottom: 2%;">
        <div id="app" class="row main left-align">
            <div id="content" class="col s12 m12 l8 xl8 push-l2 pull-l1 push-xl2 pull-xl1 container grey lighten-5">
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
