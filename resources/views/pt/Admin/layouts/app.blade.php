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
    <ul id="slide-out" class="sidenav">
        <li><div class="user-view">
          <div class="background">
            <img src="images/office.jpg">
          </div>
          <a href="#user"><img class="circle" src="images/yuna.jpg"></a>
          <a href="#name"><span class="white-text name">John Doe</span></a>
          <a href="#email"><span class="white-text email">jdandturk@gmail.com</span></a>
        </div></li>
        <li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
        <li><a href="#!">Second Link</a></li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
      </ul>
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
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
<script>
    $(document).ready(function(){
    $('.sidenav').sidenav();
  });
</script>
@yield('script')
</body>
</html>
