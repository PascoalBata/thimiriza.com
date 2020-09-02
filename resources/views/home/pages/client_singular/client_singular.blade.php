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
                    <button type="button" onclick="displayClientSingularTable()" class="waves-effect waves-light btn-small">
                        {{ __('Clientes') }}
                </div>                        
            </div>
        </form>
    </div>
</div>
<div class="row" id="client_singular_table" style="display: block;">
    <div class="col s12 m12 l12" style="overflow-x: scroll;">
        <table class="highlight">
            <thead>
                <tr>
                    <th style="text-align: center;">{{ __('Nome') }}</th>
                    <th style="text-align: center;">{{ __('Email') }}</th>
                    <th style="text-align: center;">{{ __('Telefone') }}</th>
                    <th style="text-align: center;">{{ __('NUIT') }}</th>
                    <th style="text-align: center;">{{ __('Endereço') }}</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients_singular as $client_singular)
                <tr>
                    <td>{{$client_singular->name}} {{$client_singular->surname}}</td>
                    <td style="text-align: left;">{{$client_singular->email}}</td>
                    <td style="text-align: left;">{{$client_singular->phone}}</td>
                    <td style="text-align: center;">{{$client_singular->nuit}}</td>
                    <td style="text-align: left;">{{$client_singular->address}}</td>
                    <td style="text-align: right;">
                        <a class="modal-trigger waves-effect waves-light btn-small" href="#edit_client_singular_modal" onclick="editClientSingular(this, {{$client_singular->id}}, '{{$client_singular->name}}', '{{$client_singular->surname}}')" style="width: 100%;">editar</a>
                        <a class="modal-trigger waves-effect waves-light btn-small red darken-3" href="#remove_client_singular_modal" onclick="removeClientSingular(this, {{$client_singular->id}})" style="width: 100%;">remover</a>
                    </td>
                </tr>                        
                @endforeach
            </tbody>
        </table>
        {!! $clients_singular->links() !!}
    </div>
</div>
<div id="edit_client_singular_modal" tabindex="-1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>{{ __('Actualizar Cliente Empresarial')}}</h4>
        <p></p>
        <form method="POST" id="editClientSingularNameForm" name="editClientSingularNameForm" action="{{ route('edit_client_singular_name') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m3 l3">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                    <input required id="name"  type="text" class="black-text" name="name" value="{{ old('name') }}" autofocus>
                </div>
                <div class="input-field col s12 m3 l3">
                    <label for="surname" class="black-text">{{ __('Apeldio') }}</label>
                    <input required id="surname"  type="text" class="black-text" name="surname" value="{{ old('surname') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                     <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularEmailForm" name="editClientSingularEmailForm" action="{{ route('edit_client_singular_email') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="email" class="black-text">{{ __('Email') }}</label>
                    <input required id="email"  type="email" class="black-text" name="email" value="{{ old('email') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularPhoneForm" name="editClientSingularPhoneForm" action="{{ route('edit_client_singular_phone') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input required id="phone"  type="text" class="black-text" name="phone" value="{{ old('phone') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularNuitForm" name="editClientSingularNuitForm" action="{{ route('edit_client_singular_nuit') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                    <input required id="nuit"  type="number" class="black-text" name="nuit" value="{{ old('nuit') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularAddressForm" name="editClientSingularAddressForm" action="{{ route('edit_client_singular_address') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="address" class="black-text">{{ __('Endereço') }}</label>
                    <input required id="address"  type="text" class="black-text" name="address" value="{{ old('address') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fechar</a>
    </div>
</div>
<div id="remove_client_singular_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="removeClientSingularForm" name="removeClientSingularForm" action="{{ route('remove_client_singular') }}">
        <div class="modal-content">
            <h4>{{ __('Remover Cliente Singular')}}</h4>
            <p>{{__('Tem certeza que deseja remover este cliente singular?')}}</p>
            <p>{{__('Atenção: Ao remover este cliente, todas as facturas pertencentes ao mesmo serão eliminadas!')}}</p>
            @method('DELETE')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <input id="client_singular" type="text" class="black-text" name="client_singular" value="{{ old('client_singular') }}" autofocus disabled>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="waves-effect waves-light btn-small " >
                {{ __('SIM') }}
                <i class="material-icons left"></i>
            </button>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('NÃO') }}</a>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
    function editClientSingular(button, id, name, surname){
        var tr = button.parentElement.parentElement;
        editClientSingularNameForm.id.value = id;
        editClientSingularNuitForm.id.value = id;
        editClientSingularPhoneForm.id.value = id;
        editClientSingularEmailForm.id.value = id;
        editClientSingularAddressForm.id.value = id;
        editClientSingularNameForm.name.value = name;
        editClientSingularNameForm.surname.value = surname;
        editClientSingularEmailForm.email.value = tr.cells[1].innerHTML;
        editClientSingularPhoneForm.phone.value = tr.cells[2].innerHTML;
        editClientSingularNuitForm.nuit.value = tr.cells[3].innerHTML;
        editClientSingularAddressForm.address.value = tr.cells[4].innerHTML;
    }

    function removeClientSingular(button, id){
        var tr = button.parentElement.parentElement;
        removeClientSingularForm.id.value = id;
        removeClientSingularForm.client_singular.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
    }

    function displayClientEnterpriseTable(){
        if(document.getElementById('client_singular_table').style.display === 'none'){
            document.getElementById('client_singular_table').style.display = 'block';
        }else{
            document.getElementById('client_singular_tablele').style.display = 'none';
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
    @if (session('client_singular_notification'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('client_singular_notification') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
