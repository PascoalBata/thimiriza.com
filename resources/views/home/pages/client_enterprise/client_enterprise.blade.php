@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Cliente Empresarial') }}</strong></h1>
        </div>
    </div>
    <div class="row" style="padding-bottom: 5%">
        <form method="POST" action="{{ route('store_client_enterprise') }}">
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <select id="type" name="type" onchange="changeClient(this.value)">
                        <optgroup label="{{__('Tipo')}}">
                            <option value="ENTERPRISE" selected>Empresarial</option>
                            <option value="SINGULAR">Singular</option>
                        </optgroup>
                    </select>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="email" class="black-text">{{ __('Email') }}</label>
                    <input id="email" type="email" class="black-text" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
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
                    <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                    <input id="nuit" type="number" class="black-text" name="nuit" value="{{ old('nuit') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input id="phone" type="number" class="black-text" name="phone" value="{{ old('phone') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="address" class="black-text">{{ __('Endereço') }}</label>
                    <input id="address" type="text" class="black-text" name="address" value="{{ old('address') }}" required>
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
                    <button type="button" class="waves-effect waves-light btn-small">
                        {{ __('Produtos') }}
                        <i class="material-icons right"></i>
                    </button>
                </div>                        
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script>
    function changeClient(value){
        if(value === 'ENTERPRISE'){
            window.history.replaceState(null, 'Thimiriza', '/clients_enterprise');
            url = window.location.href;
            window.location = url;
            }
        if(value === 'SINGULAR'){
            url = window.history.replaceState(null, 'Thimiriza', '/clients_singular');
            url = window.location.href;
            window.location = url;
        }
    }
</script>
    @if (session('view_client_enterprise_register_status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('view_client_enterprise_register_status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
