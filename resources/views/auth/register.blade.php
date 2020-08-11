@extends('layouts.app')

@section('content')
<div class="container grey lighten-5" style="opacity: 80%; transform: translateY(2%);">
    <div class="row">
        <div class="col s12 m12 l12 center-align">
            <div class="center-align">{{ __('Register') }}</div>
        </div>
    </div>
    <div class="container row">
        <div class="col s12 m12 l12">
            <form method="POST" action="{{ route('register') }}" onsubmit="return submeter()">
                @csrf
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <label for="name" class="black-text">{{ __('Name') }}</label>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <select id="type" name="type">
                            <option value="" disabled selected>Tipo de Empresa</option>
                            <option value="NORMAL">Normal</option>
                            <option value="ISPC">ISPC</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        <label for="email" class="black-text">{{ __('E-Mail Address') }}</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <label for="password" class="black-text">{{ __('Password') }}</label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <label for="password-confirm" class="black-text">{{ __('Confirm Password') }}</label>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $('select').formSelect();
    });
    
    function submeter() {
        if (!(document.getElementById('empresa_tipo').value === "ISPC" ||
            document.getElementById('empresa_tipo').value === "NORMAL")) {
                M.toast({html: 'Escolha o tipo de Empresa.', classes: 'rounded', displayLength: 1000});
            return false;
        }
        if(document.getElementById('senha_confirmacao').value !== document.getElementById('empresa_senha').value){
            M.toast({html: 'As senhas devem ser as mesmas.', classes: 'rounded', displayLength: 1000});
            return false;
        }
        return true;
    }
</script>
@if (session('status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
@endif
@endsection
