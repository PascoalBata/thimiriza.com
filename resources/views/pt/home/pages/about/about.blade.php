@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row">
        <div class="col-12 text-center mt-5">
            <h1 class="display-4"><strong>Sobre Nós</strong></h1>
        </div>
    </div>
    <div class="row justify-content-center mb-5">
        <div class="col-sm-12 col-md-10 col-lg-8">
            <h4>Tsandzaya Digital</h4>
            <hr style="border: 2px solid green; border-radius: 3px;">
            <div class="form-row">
                <p class="h5">Empresa Moçambicana focada no desenvolvimento de soluções tecnológicas e prestação de
                    serviços.</p>
            </div>
        </div>
        <div class="col-sm-12 col-md-10 col-lg-8">
            <h4>Thimiriza Application</h4>
            <hr style="border: 2px solid green; border-radius: 3px;">
            <div class="form-row">
                <p class="h5">Aplicativo de facturação e gestão financeira, baseado na simplicidade de uso,
                    acessibilidade à partir da qualquer dispositivo e em qualquer lugar.</p>

            </div>
        </div>
        <div class="col-sm-12 col-md-10 col-lg-8">
            <h4>Contactos</h4>
            <hr style="border: 2px solid green; border-radius: 3px;">
            <div class="form-row">
                <div class="col-sm-3">
                    <p class="footer-content">
                        <svg id="i-telephone" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32"
                            height="32" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2">
                            <path d="M3 12 C3 5 10 5 16 5 22 5 29 5 29 12 29 20 22 11 22 11 L10 11 C10 11 3 20 3 12 Z M11 14 C11 14 6 19 6 28 L26 28 C26 19 21 14 21 14 L11 14 Z"/>
                            <circle cx="16" cy="21" r="4"/>
                        </svg>
                        (+258) 848100344
                    </p>

                    <p class="footer-content">
                        <svg id="i-mail" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32"
                            fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M2 26 L30 26 30 6 2 6 Z M2 6 L16 16 30 6"/>
                        </svg>
                        info@tsandzaya.com
                    </p>

                </div>

            </div>
        </div>
        <div class="col-sm-12 col-md-10 col-lg-8">
            <h4>Endereço</h4>
            <hr style="border: 2px solid green; border-radius: 3px;">
            <div class="form-row">

                <p class="">
                    <svg id="i-location" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32"
                        fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <circle cx="16" cy="11" r="4"/>
                        <path d="M24 15 C21 22 16 30 16 30 16 30 11 22 8 15 5 8 10 2 16 2 22 2 27 8 24 15 Z"/>
                    </svg>
                    Avenida: Kim Ill sung, nr <sup>o</sup>.83, 1<sup>o</sup> andar
                </p>

            </div>
        </div>
        <div class="col-sm-12 col-md-10 col-lg-8">
            <h4>Siga-nos</h4>
            <hr style="border: 2px solid green; border-radius: 3px;">
            <div class="form-row">

                <div class="grid-item">

                    <a href="https://www.facebook.com/thimirizaSolutions/">
                        <i class="fa fa-facebook-square" style="font-size:40px;color:blue"></i>
                    </a>

                    <a href="https://www.instagram.com/thimirizasolutions/">
                        <i class="fa fa-instagram" style="font-size:40px;color:red"></i>
                    </a>

                    <a href="https://www.linkedin.com/feed/">
                        <i class="fa fa-linkedin-square" style="font-size:40px;color:blue"></i>
                    </a>

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-10 col-lg-8">

            <div class="form-row">
                <a href="https://www.tsandzaya.com/">
                    <svg id="i-link" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32"
                        fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2">
                        <path d="M18 8 C18 8 24 2 27 5 30 8 29 12 24 16 19 20 16 21 14 17 M14 24 C14 24 8 30 5 27 2 24 3 20 8 16 13 12 16 11 18 15"/>
                    </svg>
                </a> Tsandzaya Digital

            </div>
        </div>
    </div>
</div>
@endsection
