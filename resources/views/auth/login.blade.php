@extends('auth.layouts.app')

@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(20%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Login') }}</strong></h1>
        </div>
    </div>
    <div class="row" style="margin-left: 15%; margin-right: 15%; padding-bottom: 5%">
        <div class="col s12 m12 l12">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <label for="email" class="black-text">{{ __('Utilizador ID ou E-mail') }}</label>
                        <input id="email" type="text" class="black-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="red-text" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <label for="password" class="black-text">{{ __('Senha') }}</label>
                        <input id="password" type="password" class="black-text @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <label class="black-text" for="remember">
                            <input input type="checkbox" class="filled-in" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>{{ __('Remember me') }}</span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                    </div>                        
                </div>
                <div>
                    @if (Route::has('new_company'))
                        <a href="{{ route('new_company') }}">
                            {{ __('Criar conta') }}
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
@endsection
@section('script')
    @if (session('status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
