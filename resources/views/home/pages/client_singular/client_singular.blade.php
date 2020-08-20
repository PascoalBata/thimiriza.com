@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Produtos') }}</strong></h1>
        </div>
    </div>
    <div class="row" style="padding-bottom: 5%">
        <form method="POST" action="{{ route('store_product') }}">
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
                    <label for="quantity" class="black-text">{{ __('Quantidade') }}</label>
                    <input id="quantity" type="text" class="black-text" name="quantity" value="{{ old('quantity') }}" required>
                    @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="price" class="black-text">{{ __('Preço') }}</label>
                    <input id="price" type="text" class="black-text" name="price" value="{{ old('price') }}" required>
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
    @if (session('product_register_status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('product_register_status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
