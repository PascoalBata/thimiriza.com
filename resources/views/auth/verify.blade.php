@extends('pt.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">{{ __('Verificação de Email') }}</span>
                    {{ __('Antes der prosseguir, por favor verifique o seu Email.') }}
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('O link de verificação foi enviado para o teu Email') }}
                        </div>
                    @endif
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="waves-effect waves-light btn-small">{{ __('clique aqui para requisitar o Email de verificação') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
