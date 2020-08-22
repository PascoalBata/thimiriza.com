@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
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
                    <button type="button" class="waves-effect waves-light btn-small" onclick="displayTable()">
                        {{ __('Serviços') }}
                        <i class="material-icons right"></i>
                    </button>
                </div>                        
            </div>
        </form>
    </div>
    <div class="row" id="service_table" style="display: block;">
        <div class="col s12 m12 l12">
            <table class="highlight">
                <thead>
                    <tr>
                        <th>{{ __('Nome') }}</th>
                        <th>{{ __('Descrição') }}</th>
                        <th>{{ __('Preço') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                    <tr>
                        <td>{{$service->name}}</td>
                        <td>{{$service->description}}</td>
                        <td>{{number_format($service->price, 2, ',', '.')}}</td>
                        <td><a class="modal-trigger" href="#service_modal">editar</a></td>
                        <td><a href="#">remover</a></td>
                    </tr>                        
                    @endforeach
                </tbody>
            </table>
            {!! $services->links() !!}
        </div>
    </div>
</div>
<div id="service_modal" class="modal modal-fixed-footer">
    <form method="POST" action="{{ route('edit_service', $service->id) }}">
        <div class="modal-content">
            <h4>{{ __('Actualizar Serviço')}}</h4>
            <p>Altere somente os campos que pretende actualizar.</p>
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                <input id="name" value="{{$service->name}}" type="text" class="black-text" name="name" value="{{ old('name') }}">
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="description" class="black-text">{{ __('Descrição') }}</label>
                    <input id="description" value="{{$service->description}}" type="text" class="black-text" name="description" value="{{ old('description') }}">
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="price" class="black-text">{{ __('Preço') }}</label>
                    <input id="price" value="{{$service->price}}" type="number" class="black-text" name="price" value="{{ old('price') }}">
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
    function displayTable(){
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

    @if (session('service_register_status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('service_register_status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
