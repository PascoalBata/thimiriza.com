@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('style')
    <style>
        div .text-1 {
            font-size: 20px;
        }

        div .text-2 {
            font-size: 18px;
        }

    </style>
@endsection
@section('content')
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Empresa') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10%;">
            <div class="row" style="margin-bottom: 0%;">
                <h5>{{ __('Dados Gerais') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Nome') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->name }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Tipo') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->type }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Email') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->email }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Telefone') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->phone }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('NUIT') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->nuit }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Endereço') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->address }}</label>
                </div>
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <div class="row" style="margin-bottom: 0%;">
                <h5 style="margin-top: 5%;">{{ __('Dados Bancários') }}</h5>
                <hr class="teal accent-4" style="border: 2px solid transparent; border-radius: 3px;">
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Titular') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->bank_account_owner }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Número da conta') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->bank_account_number }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('NIB') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->bank_account_nib }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-1">{{ __('Nome') }}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label class="black-text text-2">{{ $company->bank_account_name }}</label>
                </div>
            </div>
        </div>
        @if ($privileges === 'TOTAL')
            <div class="row">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <a class="modal-trigger waves-effect waves-light btn-small" href="#update_modal">
                            {{ __('Actualizar') }}
                            <i class="material-icons right">update</i>
                        </a>
                        <a class="modal-trigger waves-effect waves-light btn-small" href="#payment_modal">
                            {{ __('Pagamento') }}
                            <i class="material-icons right">payment</i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div id="update_modal" tabindex="-1" class="modal modal-fixed-footer">
        <form method="POST" id="updateCompanyForm" name="updateCompanyForm" action="{{ route('edit_company') }}"
            enctype="multipart/form-data">
            <div class="modal-content">
                <h4>{{ __('Actualizacao de dados') }}</h4>
                @method('PUT')
                @csrf
                <input id="id" hidden type="text" class="black-text" name="id" value="{{ $company->id }}" required>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="name" class="black-text">{{ __('Nome') }}</label>
                        <input id="name" type="text" class="black-text" name="name" value="{{ $company->name }}" required>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <select id="type" name="type">
                            @if ($company->type == 'NORMAL')
                                <optgroup label="{{ __('Tipo') }}">
                                    <option selected value="NORMAL">NORMAL</option>
                                    <option value="ISPC">ISPC</option>
                                </optgroup>
                            @endif
                            @if ($company->type == 'ISPC')
                                <optgroup label="{{ __('Tipo') }}">
                                    <option value="NORMAL">NORMAL</option>
                                    <option selected value="ISPC">ISPC</option>
                                </optgroup>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="email" class="black-text">{{ __('Email') }}</label>
                        <input id="email" type="email" class="black-text" name="email" value="{{ $company->email }}"
                            required>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                        <input id="phone" type="number" class="black-text" name="phone" value="{{ $company->phone }}"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                        <input id="nuit" type="number" class="black-text" name="nuit" value="{{ $company->nuit }}" required>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="address" class="black-text">{{ __('Endereco') }}</label>
                        <input id="address" type="text" class="black-text" name="address" value="{{ $company->address }}"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12 file-field">
                        <div class="btn">
                            <span>{{ __('Carregar logotipo') }}</span>
                            <input type="file" class="custom-file-input" id="logo" name="logo"
                                accept="image/jpeg, image/png">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="bank_account_owner" class="black-text">{{ __('Titular da conta') }}</label>
                        <input id="bank_account_owner" type="text" class="black-text" name="bank_account_owner"
                            value="{{ $company->bank_account_owner }}" required>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="bank_account_number" class="black-text">{{ __('Numero da conta') }}</label>
                        <input id="bank_account_number" type="number" class="bank_black-text" name="bank_account_number"
                            value="{{ $company->bank_account_number }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="bank_account_nib" class="black-text">{{ __('NIB') }}</label>
                        <input id="bank_account_nib" type="number" class="black-text" name="bank_account_nib"
                            value="{{ $company->bank_account_nib }}" required>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="bank_account_name" class="black-text">{{ __('Nome do banco') }}</label>
                        <input id="bank_account_name" type="text" class="black-text" name="bank_account_name"
                            value="{{ $company->bank_account_name }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="waves-effect waves-light btn-small ">
                    {{ __('SALVAR') }}
                    <i class="material-icons left"></i>
                </button>
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('FECHAR') }}</a>
            </div>
        </form>
    </div>
    <div id="payment_modal" tabindex="-1" class="modal">
        <form method="POST" id="paymentForm" name="paymentForm" action="{{ route('company_payment') }}">
            <div class="modal-content">
                <h4>{{ __('Pagamento (MPESA)') }}</h4>
                <p>{{ __('Será cobrado um valor de 750mt') }}</p>
                @method('PUT')
                @csrf
                <input id="id" hidden type="text" class="black-text" name="id" value="{{ $company->id }}" required>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                        <input id="phone" type="number" class="black-text" name="phone" value="{{ $company->phone }}"
                            required disabled>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <button type="button" class="waves-effect waves-light btn-small"
                            onclick="paymentForm.phone.value = ''; paymentForm.phone.disabled = false;">
                            {{ __('Mudar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="waves-effect waves-light btn-small ">
                        {{ __('PAGAR') }}
                        <i class="material-icons left"></i>
                    </button>
                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('FECHAR') }}</a>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.modal').modal();
        });
    </script>
    @if (session('company_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('company_notification') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
