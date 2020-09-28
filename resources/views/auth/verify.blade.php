@extends('pt.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verificação de Email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('O link de verificação foi enviado para o teu Email') }}
                        </div>
                    @endif

                    {{ __('Antes que prossiga, por favor verifica o seu Email.') }}
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('clique aqui para requisitar o Email de verificação') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
