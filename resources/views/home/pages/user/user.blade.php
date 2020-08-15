@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Utilizadores') }}</strong></h1>
        </div>
    </div>
    <div class="row" style="padding-bottom: 5%">
        <form method="POST" action="{{ route('store_user', $fullname) }}">
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                    <input id="name" type="text" class="black-text" name="name" value="{{ old('name') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="surname" class="black-text">{{ __('Apelido') }}</label>
                    <input id="surname" type="text" class="black-text" name="surname" value="{{ old('surname') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <select id="gender" name="gender">
                        <optgroup label="{{__('Género')}}">
                            <option value="HOMEM">Homem</option>
                            <option value="MULHER">Mulher</option>
                        </optgroup>
                    </select>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="birthdate" class="black-text">{{ __('Birthdate') }}</label>
                    <input id="birthdate" type="text" class="black-text" name="birthdate" value="{{ old('birthdate') }}" required onfocus="(this.type='date')" onblur="(this.type='text')">
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="email" class="black-text">{{ __('Email') }}</label>
                    <input id="email" type="email" class="black-text" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input id="phone" type="number" data-target="20" class="black-text" name="phone" value="{{ old('phone') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <label for="address" class="black-text">{{ __('Endereço/Morada') }}</label>
                    <input id="address" type="text" class="black-text" name="address" value="{{ old('address') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="password" class="black-text">{{ __('Senha') }}</label>
                    <input id="password" type="password" class="black-text" name="password" value="{{ old('password') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="confirm_password" class="black-text">{{ __('Confirme a senha') }}</label>
                    <input id="confirm_password" type="password" class="black-text" name="confirm_password" value="{{ old('confirm_password') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12">
                    <button type="submit" class="waves-effect waves-light btn-small">
                        {{ __('Salvar') }}
                        <i class="material-icons right">archive</i>
                    </button>
                    <button type="reset" class="waves-effect waves-light btn-small">
                        {{ __('Limpar') }}
                        <i class="material-icons right"></i>
                    </button>
                    <button type="button" id="all_users_btn" class="waves-effect waves-light btn-small">
                        {{ __('Ver todos') }}
                        <i class="material-icons right"></i>
                    </button>
                </div>                        
            </div>
        </form>

        <div id='all_users'>
            TODOS oS SERVICES
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $("#all_users_btn").click(function(){
      $("#all_users").toggle();
    });
</script>
@endsection
