@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
    @php
    $sale_type = 'PRODUCT';
    @endphp
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Vendas') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <form method="POST" name="saleForm" action="{{ route('store_sale') }}">
                @csrf
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="client" class="black-text">{{ __('Cliente') }}</label>
                        <input id="client" list="clients" type="text" autocomplete="off" class="black-text" name="client"
                            @if ($hasSales)
                        value="{{ $actual_client }}"
                    @else
                        value="{{ old('client') }}"
                        @endif
                        required>
                        <datalist id="clients">
                            @foreach ($clients_singular as $client_singular)
                                <option
                                    value='{{ $client_singular->name }} {{ $client_singular->surname }} {{ __('===') }} {{ $client_singular->email }}'>
                            @endforeach
                            @foreach ($clients_enterprise as $client_enterprise)
                                <option
                                    value='{{ $client_enterprise->name }} {{ __('===') }} {{ $client_enterprise->email }}'>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <select id="sale_type" name="sale_type" onchange="saleType(this)">
                            <optgroup label="{{ __('Tipo') }}">
                                <option value="PRODUCT">{{ __('Produto') }}</option>
                                <option value="SERVICE">{{ __('Serviço') }}</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="name" id="sale_type_label" name="sale_type_label"
                            class="black-text">{{ __('Produto') }}</label>
                        <input id="name" list="list_products" type="text" autocomplete="off" class="black-text" name="name"
                            value="{{ old('name') }}" required>
                        <datalist id="list_products">
                            @foreach ($products as $product)
                                <option value='{{ $product->name }} {{ __('===') }} {{ $product->description }}'>
                            @endforeach
                        </datalist>
                        <datalist id="list_services">
                            @foreach ($services as $service)
                                <option value='{{ $service->name }} {{ __('===') }} {{ $service->description }}'>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="quantity" class="black-text">{{ __('Quantidade') }}</label>
                        <input id="quantity" type="number" class="black-text" name="quantity" value="{{ old('quantity') }}"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="discount" class="black-text">{{ __('Desconto') }}</label>
                        <input id="discount" type="number" class="black-text" name="discount" value="{{ old('discount') }}"
                            required>
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
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row" id="service_table" style="display: block;">
        <div class="col s12 m12 l12">
            <table class="highlight">
                <thead>
                    <tr>
                        <th>{{ __('Nome') }}</th>
                        <th style="text-align: center;">{{ __('Descrição') }}</th>
                        <th style="text-align: center;">{{ __('Quantidade') }}</th>
                        <th style="text-align: right;">{{ __('Preço unit.') }}</th>
                        <th style="text-align: center;">{{ __('Desconto') }}</th>
                        <th style="text-align: right;">{{ __('Preço') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    @endphp
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->name }}</td>
                            <td style="text-align: center;">{{ $sale->description }}</td>
                            <td style="text-align: center;">{{ $sale->quantity }}</td>
                            <td style="text-align: right;">{{ number_format($sale->price_unit, 2, ',', '.') }}
                                {{ __('MT') }}</td>
                            <td style="text-align: center;">{{ __('0%') }}</td>
                            <td style="text-align: right;">{{ number_format($sale->price, 2, ',', '.') }} {{ __('MT') }}
                            </td>
                            <td style="text-align: right;">
                                <a class="modal-trigger waves-effect waves-light btn-small" href="#edit_product_modal"
                                    onclick="editProduct(this, {{ $product->id }}, {{ $product->price }})">editar</a>
                                <a class="modal-trigger waves-effect waves-light btn-small red darken-3"
                                    href="#remove_product_modal"
                                    onclick="removeProduct(this, {{ $product->id }})">remover</a>
                            </td>
                        </tr>
                        @php
                        $total = $total + $sale->price;
                        @endphp
                    @endforeach
                    <tr>
                        <td style="text-align: left; font-weight: bold;">{{ __('TOTAL') }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($total, 2, ',', '.') }}
                            {{ __('MT') }}</td>
                    </tr>
                </tbody>
            </table>
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
        function editProduct(button, id, price) {
            var tr = button.parentElement.parentElement;
            editProductNameForm.id.value = id;
            editProductDescriptionForm.id.value = id;
            editProductPriceForm.id.value = id;
            editProductQuantityForm.id.value = id;
            editProductNameForm.name.value = tr.cells[0].innerHTML;
            editProductDescriptionForm.description.value = tr.cells[1].innerHTML;
            editProductQuantityForm.quantity.value = tr.cells[2].innerHTML;
            editProductPriceForm.price.value = price;
        }

        function removeProduct(button, id) {
            var tr = button.parentElement.parentElement;
            removeProductForm.id.value = id;
            removeProductForm.product.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
        }

        function saleType(click) {
            if (click.value == 'SERVICE') {
                document.getElementById('sale_type_label').innerText = 'Serviço';
                document.getElementById('name').setAttribute('list', 'list_services')
            }
            if (click.value == 'PRODUCT') {
                document.getElementById('sale_type_label').innerText = 'Produto';
                document.getElementById('name').setAttribute('list', 'list_products')
            }
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if (session('sale_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('
                    sale_notification ') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
