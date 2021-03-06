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
                <input id="description" type="text" class="black-text" name="description"
                    value="{{ old('description') }}" required>
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
                <input id="quantity" type="number" class="black-text" name="quantity" value="{{ old('quantity') }}"
                    required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="price" class="black-text">{{ __('Preço') }}</label>
                <input id="price" type="number" class="black-text" name="price" value="{{ old('price') }}" required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            @if ($company_type === 'NORMAL')
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <p>
                        <label class="black-text">
                            <input type="checkbox" id="product_iva" name="product_iva"/>
                            <span>{{__('Incluir IVA')}}</span>
                        </label>
                    </p>
                </div>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <button style="margin-top: 2px;" type="submit" class="waves-effect waves-light btn-small">
                    {{ __('Salvar') }}
                    <i class="material-icons right">archive</i>
                </button>
                <button style="margin-top: 2px;" type="reset" class="waves-effect waves-light btn-small">
                    {{ __('Limpar') }}
                    <i class="material-icons right"></i>
                </button>
                <a class="waves-effect waves-light btn-small modal-trigger" style="margin-top: 2px;"
                    href="{{route('index_product')}}">{{ __('Produtos') }}</a>
            </div>
        </div>
    </form>
</div>
