@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Empresa') }}</strong></h1>
        </div>
    </div>
    {{__('Dados')}}
    <div class="row" style="padding-bottom: 5%">
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Nome') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->name}}</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Tipo') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->type}}</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Email') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->email}}</label>
            </div>
        </div>      
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Telefone') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->phone}}</label>
            </div>
        </div>  
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('NUIT') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->nuit}}</label>
            </div>
        </div>  
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Endereço') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->address}}</label>
            </div>
        </div>
        <br/>
        {{__('Dados Bancários')}}
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Titular') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->bank_account_owner}}</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Número da conta') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->bank_account_number}}</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('NIB') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->bank_account_nib}}</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{ __('Nome') }}</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label class="black-text">{{$company->bank_account_name}}</label>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <button type="submit" class="waves-effect waves-light btn-small">
                    {{ __('Actualizar') }}
                    <i class="material-icons right">update</i>
                </button>
                <button type="button" class="waves-effect waves-light btn-small">
                    {{ __('Pagamento') }}
                    <i class="material-icons right">payment</i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>

</script>
@if (session('company_notification'))
<div class="alert alert-success">
    <script>
        M.toast({html: '{{ session('company_notification') }}', classes: 'rounded', displayLength: 1000});
    </script>
</div>
@endif
@endsection
