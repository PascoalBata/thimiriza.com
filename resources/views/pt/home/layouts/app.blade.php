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
                                <img style="height:100%; width:100%;" src="@yield('logo')">
                            </div>
                            <a href="#"><span class="white-text name">@yield('username')</span></a>
                            <a href="#"><span style="padding-bottom: 0px;"
                                    class="white-text email">@yield('user_email')</span></a>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link black-text dropdown-trigger" href="#" data-target='dropdown1'>
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
                                <a href="{{ route('view_sale') }}">
                                    {{ __('Criar') }} <i class="small material-icons">add_shopping_cart</i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('view_credit') }}">
                                    {{ __('Pagas') }} <i class="small material-icons">credit_card</i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('view_debit') }}">
                                    {{ __('Não pagas') }} <i class="small material-icons">event_busy</i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link black-text dropdown-trigger" href="#" data-target='dropdown2'>
                            {{ __('Notas') }} <i class="small material-icons">note</i>
                        </a>
                        <ul id='dropdown2' class='dropdown-content'>
                            <li>
                                <a href="#">
                                    {{ __('Notas') }} <i class="small material-icons">note</i>
                                </a>
                            </li>
                            <li class="divider" tabindex="-1"></li>
                            @if ($privilege !== 'ADMIN')
                            <li>
                                <a href="{{ route('view_invoice_note') }}">
                                    {{ __('Criar') }} <i class="small material-icons">create</i>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('index_invoice_note') }}">
                                    {{ __('Lista') }} <i class="small material-icons">list</i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link black-text dropdown-trigger" href="#" data-target='dropdown3'>
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
                                <a class="nav-link black-text sidenav-close" href="{{ route('view_product') }}">
                                    {{ __('Produtos') }} <i class="small material-icons">shopping_basket</i>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link black-text sidenav-close" href="{{ route('view_service') }}">
                                    {{ __('Serviços') }} <i class="small material-icons">shop</i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link black-text dropdown-trigger" href="#" data-target='dropdown4'>
                            {{ __('Clientes') }} <i class="small material-icons">supervisor_account</i>
                        </a>
                        <ul id='dropdown4' class='dropdown-content'>
                            <li>
                                <a href="#">
                                    {{ __('Clientes') }} <i class="small material-icons">supervisor_account</i>
                                </a>
                            </li>
                            <li class="divider" tabindex="-1"></li>
                            <li>
                                <a class="nav-link black-text sidenav-close" href="{{ route('view_client_singular') }}">
                                    {{ __('Singulares') }} <i class="small material-icons">person</i>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link black-text sidenav-close"
                                    href="{{ route('view_client_enterprise') }}">
                                    {{ __('Empresariais') }} <i class="small material-icons">business_center</i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link black-text dropdown-trigger" href="#" data-target='dropdown5'>
                            {{ __('Relatórios') }} <i class="small material-icons">assignment</i>
                        </a>
                        <ul id='dropdown5' class='dropdown-content'>
                            <li>
                                <a href="#">
                                    {{ __('Relatórios') }} <i class="small material-icons">assignment</i>
                                </a>
                            </li>
                            <li class="divider" tabindex="-1"></li>
                            <li>
                                <a class="nav-link black-text sidenav-close"
                                href="{{route('view_report')}}">
                                    {{ __('Geral') }} <i class="small material-icons">assessment</i>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link black-text sidenav-close"
                                href="{{route('view_tax')}}">
                                    {{ __('Impostos') }} <i class="small material-icons">content_paste</i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link black-text sidenav-close" href="{{ route('view_user') }}">
                            {{ __('Utilizadores') }} <i class="small material-icons">group_add</i>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link black-text sidenav-close" href="{{ route('view_company') }}">
                            {{ __('Empresa') }} <i class="small material-icons">account_balance</i>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link black-text sidenav-close" href="{{ route('view_about') }}">
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
