@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Sobre nós') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10%;">
            <div class="row" style="margin-bottom: 0%;">
                <h5>{{ __('Tsandzaya Digital') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
                <p>Empresa Moçambicana focada no desenvolvimento de soluções tecnológicas e prestação de
                    serviços.</p>
            </div>
            <div class="row" style="margin-bottom: 0%;">
                <h5>{{ __('Thimiriza Application') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
                    <p>Aplicativo de facturação e gestão financeira, baseado na simplicidade de uso,
                        acessibilidade à partir da qualquer dispositivo e em qualquer lugar.</p>

            </div>
            <div class="row" style="margin-bottom: 0%;">
                <h5>{{ __('Contactos') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
                            <p>
                                <i class="material-icons">phone</i>
                                            (+258) 82 092 6335
                            </p>
                            <p>
                                <i class="material-icons">mail</i>
                                <a href="mailto:info@tsandzaya.com">
                                    info@tsandzaya.com
                                </a>
                            </p>
                            <p>
                            <i class="material-icons">language</i>
                                <a href="https://www.tsandzaya.com/">
                                    Tsandzaya Digital
                                </a>
                            </p>

            </div>
            <div class="row" style="margin-bottom: 0%;">
                <h5>{{ __('Endereço') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
                <i class="material-icons">map</i>
                <a href="https://www.google.com/maps/place/Tsandzaya/@-25.9662451,32.5917748,15z/data=!4m5!3m4!1s0x0:0x16a399af2e9c4cb4!8m2!3d-25.9662451!4d32.5917748">
                    Avenida: Kim Ill sung, nr<sup>o</sup>.83, 1<sup>o</sup> andar
                </a>
            </div>
            <div class="row" style="margin-bottom: 0%;">
                <h5>{{ __('Siga-nos') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
                <a href="https://www.facebook.com/thimirizaSolutions/">
                    <i class="fab fa-facebook fa-3x"></i>
                </a>
                <a href="https://www.instagram.com/thimirizasolutions/" style="text-decoration: none;">
                    <i class="fab fa-instagram fa-3x"></i>
                </a>
                <a href="https://www.linkedin.com/feed/" style="text-decoration: none;">
                    <i class="fab fa-linkedin fa-3x"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
