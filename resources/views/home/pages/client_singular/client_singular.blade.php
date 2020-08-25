@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Cliente Singular') }}</strong></h1>
        </div>
    </div>
    <div class="row" style="padding-bottom: 5%">
        <form method="POST" action="{{ route('store_client_singular') }}">
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <select id="type" name="type" onchange="changeClient(this.value)">
                        <optgroup label="{{__('Tipo')}}">
                            <option value="ENTERPRISE">Empresarial</option>
                            <option value="SINGULAR" selected>Singular</option>
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
                    <label for="surname" class="black-text">{{ __('Apelido') }}</label>
                    <input id="surname" type="text" class="black-text" name="surname" value="{{ old('surname') }}" required>
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
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input id="phone" type="number" class="black-text" name="phone" value="{{ old('phone') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                
            </div>
            <div class="row">
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
                        {{ __('Clientes') }}
                        <i class="material-icons right"></i>
                    </button>
                </div>                        
            </div>
        </form>
    </div>
    <div class="row" id="product_table" style="display: block;">
        <div class="col s12 m12 l12" style="overflow: auto;">
            <table class="highlight striped">
                <thead>
                    <tr>
                        <th>{{ __('Nome') }}</th>
                        <th style="text-align: center;">{{ __('Email') }}</th>
                        <th style="text-align: center;">{{ __('NUIT') }}</th>
                        <th style="text-align: center;">{{ __('Telefone') }}</th>
                        <th style="text-align: center;">{{ __('Endereço') }}</th>
                        <th style="text-align: center;">{{ __('') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients_singular as $client_singular)
                    <tr>
                        <td>{{$client_singular->name}} {{$client_singular->surname}}</td>
                        <td style="text-align: center;">{{$client_singular->email}}</td>
                        <td style="text-align: right;">{{$client_singular->nuit}}</td>
                        <td style="text-align: right;">{{$client_singular->phone}}</td>
                        <td style="text-align: right;">{{$client_singular->address}}</td>
                        <td style="text-align: right;">
                            <a class="modal-trigger waves-effect waves-light btn-small" href="#client_singular_modal" onclick="clickedClientSingular(this, {{$client_singular->id}}, {{$client_singular->name}}, {{$client_singular->surname}})">editar</a>
                            <a href="#" class="waves-effect waves-light btn-small red darken-3">remover</a>
                        </td>
                    </tr>                        
                    @endforeach
                </tbody>
            </table>
            {!! $clients_singular->links() !!}
        </div>
    </div>
</div>
<div id="client_singular_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="editClientSingularForm" name="editClientSingularForm" action="{{ route('edit_client_singular') }}">
        <div class="modal-content">
            <h4>{{ __('Actualizar Cliente Singular')}}</h4>
            <p>Altere somente os campos que pretende actualizar.</p>
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('name') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                    <input id="name"  type="text" class="black-text" name="name" value="{{ old('name') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="surname" class="black-text">{{ __('Apelido') }}</label>
                    <input id="surname"  type="text" class="black-text" name="surname" value="{{ old('surname') }}" autofocus>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                    <input id="nuit" type="number" class="black-text" name="nuit" value="{{ old('nuit') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input id="phone" type="number" class="black-text" name="phone" value="{{ old('phone') }}" autofocus>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="email" class="black-text">{{ __('Email') }}</label>
                    <input id="email"  type="text" class="black-text" name="email" value="{{ old('email') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="address" class="black-text">{{ __('Endereço') }}</label>
                    <input id="address"  type="text" class="black-text" name="address" value="{{ old('address') }}" autofocus>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="waves-effect waves-green btn-flat">
                {{ __('Salvar') }}
                <i class="material-icons right">archive</i>
            </button>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fechar</a>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
    function clickedClientSingular(button, id, name, surname){
        var tr = button.parentElement.parentElement;
        editClientSingularForm.id.value = id;
        editClientSingularForm.name.value = name;
        editClientSingularForm.surname.value = surname;
        editClientSingularForm.email.value = tr.cells[0].innerHTML;
        editClientSingularForm.nuit.value = tr.cells[1].innerHTML;
        editClientSingularForm.phone.value = tr.cells[2].innerHTML;
        editClientSingularForm.address.value = tr.cells[4].innerHTML;
    }

    function displayTable(){
        if(document.getElementById('client_singular_table').style.display === 'none'){
            document.getElementById('client_singular_table').style.display = 'block';
        }else{
            document.getElementById('client_singular_table').style.display = 'none';
        }
    }

    $(document).ready(function(){
        $('.modal').modal();
    });

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
    @if (session('view_client_singular_register_status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('view_client_singular_register_status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
