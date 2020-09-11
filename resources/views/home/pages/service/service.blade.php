@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Serviços') }}</strong></h1>
        </div>
    </div>
    <div class="row" style="padding-bottom: 5%">
        <form method="POST" action="{{ route('store_service') }}">
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
                    <label for="description" class="black-text">{{ __('Descrição') }}</label>
                    <input id="description" type="text" class="black-text" name="description" value="{{ old('description') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="price" class="black-text">{{ __('Preço') }}</label>
                    <input id="price" type="number" class="black-text" name="price" value="{{ old('price') }}" required>
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
                    <a class="waves-effect waves-light btn-small modal-trigger" href="#modal_services">{{__('Serviços')}}</a>
                </div>                        
            </div>
        </form>
    </div>
</div>
<!-- Users Modal -->
<div id="modal_services" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>{{__('Serviços')}}</h4>
        <div class="row" id="service_table" style="display: block;">
            <div class="col s12 m12 l12">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>{{ __('Nome') }}</th>
                            <th style="text-align: center;">{{ __('Descrição') }}</th>
                            <th style="text-align: center;">{{ __('Preço') }}</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                        <tr>
                            <td>{{$service->name}}</td>
                            <td style="text-align: center;">{{$service->description}}</td>
                            <td style="text-align: center;">{{number_format($service->price, 2, ',', '.')}} {{ __('MT') }}</td>
                            <td style="text-align: right;">
                                <a style="width: 100%;" class="modal-trigger waves-effect waves-light btn-small" href="#edit_service_modal" onclick="editService(this, {{$service->id}}, {{$service->price}})">editar</a>
                                <a style="width: 100%;" class="modal-trigger waves-effect waves-light btn-small red darken-3" href="#remove_service_modal" onclick="removeService(this, {{$service->id}})">remover</a>
                            </td>
                        </tr>                        
                        @endforeach
                    </tbody>
                </table>
                {!! $services->links() !!}
            </div>
        </div>
    </div>
</div>
<div id="edit_service_modal" tabindex="-1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>{{ __('Actualizar Serviço')}}</h4>
        <p>Altere somente os campos que pretende actualizar.</p>
        <form method="POST" id="editServiceNameForm" name="editServiceNameForm" action="{{ route('edit_service_name') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                    <input id="name"  type="text" class="black-text" name="name" value="{{ old('name') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                     <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editServiceDescriptionForm" name="editServiceDescriptionForm" action="{{ route('edit_service_description') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="description" class="black-text">{{ __('Descrição') }}</label>
                    <input id="description"  type="text" class="black-text" name="description" value="{{ old('description') }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editServicePriceForm" name="editServicePriceForm" action="{{ route('edit_service_price') }}">
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="price" class="black-text">{{ __('Preço') }}</label>
                    <input id="price" type="number" class="black-text" name="price" value="{{ old('price') }}" autofocus>
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
<div id="remove_service_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="removeProductForm" name="removeServiceForm" action="{{ route('remove_service') }}">
        <div class="modal-content">
            <h4>{{ __('Remover Serviço')}}</h4>
            <p>{{__('Tem certeza que deseja remover este serviço?')}}</p>
            @method('DELETE')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <input id="service" type="text" class="black-text" name="service" value="{{ old('service') }}" autofocus disabled>
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
    function editService(button, id, price){
        var tr = button.parentElement.parentElement;
        editServiceNameForm.id.value = id;
        editServiceDescriptionForm.id.value = id;
        editServicePriceForm.id.value = id;
        editServiceNameForm.name.value = tr.cells[0].innerHTML;
        editServiceDescriptionForm.description.value = tr.cells[1].innerHTML;
        editServicePriceForm.price.value = price;
    }

    function removeService(button, id){
        var tr = button.parentElement.parentElement;
        removeServiceForm.id.value = id;
        removeServiceForm.service.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
    }

    function displayServiceTable(){
        if(document.getElementById('service_table').style.display === 'none'){
            document.getElementById('service_table').style.display = 'block';
        }else{
            document.getElementById('service_table').style.display = 'none';
        }
    }

    $(document).ready(function(){
        $('.modal').modal();
    });
</script>
    @if (session('service_notification'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('service_notification') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
