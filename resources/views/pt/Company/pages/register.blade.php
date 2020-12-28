@extends('auth.layouts.app')

@section('content')
<div class="container grey lighten-5" style="opacity: 80%;">
    <div class="card">
        <div class="card-content">
            <h4 class="center-align">{{ __('Registo de Empresa') }}</h4>
            <div class="container row">
                <div class="col s12 m12 l12">
                    <form method="POST" action="{{ route('store_company') }}">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input id="name" type="text" class="black-text validate" name="name" value="{{ old('name') }}" required autocomplete="name">
                                <label for="name" class="black-text">{{ __('Nome da empresa') }}</label>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <select id="type" name="type">
                                    <optgroup label="{{__('Tipo')}}">
                                        <option value="NORMAL">Normal</option>
                                        <option value="ISPC">ISPC</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input id="email" type="email" class="black-text validate" name="email" value="{{ old('email') }}" required autocomplete="email">
                                <label for="email" class="black-text">{{ __('E-Mail') }}</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="phone" type="tel" class="black-text validate" name="phone" value="{{ old('phone') }}" required>
                                <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input type="number" class="black-text validate" id="nuit" name="nuit"
                                       required value="{{old('nuit')}}">
                                <label for="nuit" class="black-text">NUIT</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input type="text" class="black-text validate" data-length="255" id="address" name="address"
                                       required value="{{old('address')}}">
                                <label for="address" class="black-text">Endereço</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input id="password" type="password" class="black-text validate" name="password" required autocomplete="new-password">
                                <label for="password" class="black-text">{{ __('Senha') }}</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input id="password-confirm" type="password" class="black-text validate" name="password_confirm" required autocomplete="new-password">
                                <label for="password-confirm" class="black-text">{{ __('Confirme a senha') }}</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 l6">
                                <button type="submit" class="waves-effect waves-light btn-small">
                                    {{ __('Registar') }}
                                </button>
                                <button type="reset" class="waves-effect waves-light btn-small">
                                    {{ __('Limpar') }}
                                </button>
                            </div>
                        </div>
                        <div>
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}">
                                    {{ __('Ja possui conta?') }}
                                </a>
                            @endif
                        </div>
                        <div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">
                                    {{ __('Esqueceu a senha?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $('select').formSelect();
    });
    $(document).ready(function() {
        $('input#name, input#address').characterCounter();
    });
</script>
@if (session('status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
@endif
@endsection
