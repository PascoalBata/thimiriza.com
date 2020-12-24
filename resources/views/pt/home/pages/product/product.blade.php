@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
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
                    <div class="input-field col s12 m6 l6">
                        <p>
                            <label class="black-text">
                                <input type="checkbox" id="product_iva" name="product_iva"/>
                                <span>{{__('Incluir IVA')}}</span>
                            </label>
                          </p>
                    </div>
                    @endif
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
                        <a class="waves-effect waves-light btn-small modal-trigger"
                            href="#modal_products">{{ __('Produtos') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Users Modal -->
    <div id="modal_products" class="modal bottom-sheet">
        <div class="modal-content">
            <h4>{{ __('Produtos') }}</h4>
            <div class="row" id="service_table" style="display: block;">
                <div class="col s12 m12 l12">
                    <table class="highlight">
                        <thead>
                            <tr>
                                <th>{{ __('Nome') }}</th>
                                <th style="text-align: center;">{{ __('Descrição') }}</th>
                                <th style="text-align: center;">{{ __('Quantidade') }}</th>
                                <th style="text-align: center;">{{ __('Preço') }}</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td style="text-align: center;">{{ $product->description }}</td>
                                    <td style="text-align: center;">{{ $product->quantity }}</td>
                                    <td style="text-align: center;">{{ number_format($product->price, 2, ',', '.') }}
                                        {{ __('MT') }}</td>
                                    <td style="text-align: right;">
                                        <a style="width: 100%;" class="modal-trigger waves-effect waves-light btn-small"
                                            href="#edit_product_modal">editar</a>
                                        <a style="width: 100%;"
                                            class="modal-trigger waves-effect waves-light btn-small red darken-3"
                                            href="#remove_product_modal">remover</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>
    <div id="edit_product_modal" tabindex="-1" class="modal modal-fixed-footer">
        <product_edit-component>
            <script type="text/javascript" src="js/app.js"></script>
        </product_edit-component>
        <div class="modal-content">
            <h4>{{ __('Actualizar Produto') }}</h4>
            <p>Altere somente os campos que pretende actualizar.</p>
            <form method="POST" id="editProductNameForm" name="editProductNameForm"
                action="{{ route('edit_product') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m5 l5">
                        <label for="name" class="black-text">{{ __('Nome') }}</label>
                        <input id="name" type="text" class="black-text" name="name" value="{{ old('name') }}" autofocus>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <label for="description" class="black-text">{{ __('Descrição') }}</label>
                        <input id="description" type="text" class="black-text" name="description"
                            value="{{ old('description') }}" autofocus>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m5 l5">
                        <label for="quantity" class="black-text">{{ __('Preço') }}</label>
                        <input id="quantity" type="number" class="black-text" name="quantity" value="{{ old('quantity') }}"
                            autofocus>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <label for="price" class="black-text">{{ __('Preço') }}</label>
                        <input id="price" type="number" class="black-text" name="price" value="{{ old('price') }}"
                            autofocus>
                    </div>
                </div>
                <div class="row">
                    @if ($company_type === 'NORMAL')
                        <div class="input-field col s12 m6 l6">
                            <p>
                                <label class="black-text">
                                    <input type="checkbox" id="product_iva" name="product_iva"/>
                                    <span>{{__('Incluir IVA')}}</span>
                                </label>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
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
    <div id="remove_product_modal" tabindex="-1" class="modal modal-fixed-footer">
        <form method="POST" id="removeProductForm" name="removeProductForm" action="{{ route('remove_product') }}">
            <div class="modal-content">
                <h4>{{ __('Remover Produto') }}</h4>
                <p>{{ __('Tem certeza que deseja remover este produto?') }}</p>
                @method('DELETE')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <input id="product" type="text" class="black-text" name="product" value="{{ old('product') }}"
                            autofocus disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="waves-effect waves-light btn-small ">
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

        function removeProduct(button, id) {
            var tr = button.parentElement.parentElement;
            removeProductForm.id.value = id;
            removeProductForm.product.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
        }

        function displayProductTable() {
            if (document.getElementById('product_table').style.display === 'none') {
                document.getElementById('product_table').style.display = 'block';
            } else {
                document.getElementById('product_table').style.display = 'none';
            }
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>

    @if (session('product_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('
                    product_notification ') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
