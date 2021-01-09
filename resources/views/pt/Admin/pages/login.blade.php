@extends('pt.Admin.layouts.app_login')

@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(2%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Login') }}</strong></h1>
            <h4 class="display-4 black-text"><strong>{{ __('Administração') }}</strong></h4>
        </div>
    </div>
    <div class="row" style="margin-left: 15%; margin-right: 15%; padding-bottom: 5%">
        <div class="col s12 m12 l12">
            <form method="POST" action="{{ route('login_admin') }}">
                @csrf
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <label for="user" class="black-text">{{ __('Utilizador') }}</label>
                        <input id="user" type="text" class="black-text" name="user" value="{{ old('user') }}" required autofocus>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <label for="password" class="black-text">{{ __('Senha') }}</label>
                        <input id="password" type="password" class="black-text" name="password" value="{{ old('password') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                    </div>
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
