@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
    @php
    $sale_type = 'PRODUCT';
    @endphp
    <label>{{$deadline_payment}}</label>
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Vendas') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <form method="POST" name="saleForm" action="{{ route('store_sale') }}">
                @method('POST')
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
                        <label for="discount" class="black-text">{{ __('Desconto (%)') }}</label>
                        <input id="discount" placeholder="Ex.: 12.5" type="number" class="black-text" name="discount" value="{{ old('discount') }}"
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
        <div class="col s12 m12 l12" style="overflow-x: auto;">
            <table class="highlight" style="width: 100%;">
                <thead>
                    <tr>
                        <th>{{ __('Nome') }}</th>
                        <th style="text-align: center;">{{ __('Descrição') }}</th>
                        <th style="text-align: center;">{{ __('Quantidade') }}</th>
                        <th style="text-align: right;">{{ __('Preço unit.') }}</th>
                        <th style="text-align: center;">{{ __('Desconto') }}</th>
                        @if ($company_type === 'NORMAL')
                            <th style="text-align: center;">{{ __('IVA') }}</th>
                        @endif
                        <th style="text-align: right;">{{ __('Preço') }}</th>
                        <th style="width: 5%;"></th>
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
                            <td style="text-align: right;">{{ number_format($sale->price_unit, 2, ',', '.') }}{{ __('MT') }}</td>
                            <td style="text-align: center;">{{ number_format($sale->discount_price, 2, ',', '.') }}
                                {{ __('MT') }}</td>
                            @if ($company_type === 'NORMAL')
                                <td style="text-align: center;">{{ number_format($sale->iva, 2, ',', '.') }}{{ __('MT') }}
                                </td>
                            @endif
                            <td style="text-align: right;">{{ number_format($sale->price, 2, ',', '.') }}{{ __('MT') }}
                            </td>
                            <td style="text-align: right;">
                                <a style="width:100%;" class="modal-trigger waves-effect waves-light btn-small" href="#edit_sale_item_modal"
                                    onclick="editSaleItem(this, {{ $sale->id }}, {{ $sale->quantity }}, {{ $sale->discount * 100 }})">editar</a>
                                <a style="width:100%;" class="modal-trigger waves-effect waves-light btn-small red darken-3"
                                    href="#remove_sale_item_modal"
                                    onclick="removeSaleItem(this, {{ $sale->id }})">remover</a>
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
                        @if ($company_type === 'NORMAL')
                            <td></td>
                        @endif
                        <td></td>
                        <td colspan="2" style="text-align: right; font-weight: bold;">{{ number_format($total, 2, ',', '.') }}
                            {{ __('MT') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12 l12" style="display: flex;">
            <form method="POST" action="{{ route('clean_sale') }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="waves-effect waves-light btn-small" style="margin-right: 2%;">
                    {{ __('LIMPAR') }}
                    <i class="material-icons right"></i>
                </button>
            </form>
            <form method="POST" action="{{ route('quote') }}">
                @method('POST')
                @csrf
                <button type="submit" class="waves-effect waves-light btn-small" style="margin-right: 2%;"
                @if (!$enable_sales)
                        disabled
                    @endif
                >
                    {{ __('COTAÇÃO') }}
                    <i class="material-icons right"></i>
                </button>
            </form>
            <form method="POST" action="{{ route('sell') }}">
                @method('POST')
                @csrf
                <button type="submit" class="waves-effect waves-light btn-small" style="margin-right: 2%;" @if ($logo === '')
                    disabled
                    @endif
                    @if (!$enable_sales)
                        disabled
                    @endif
                    @if ($isAdmin)
                        disabled
                    @endif
                    >
                    {{ __('VENDER') }}
                    <i class="material-icons right"></i>
                </button>
            </form>
        </div>
        <div class="col s12 m12 l12">
            <p class="black-text">{{__('NOTA: Utilizadores administrativos nao podem efectuar vendas')}}</p>
        </div>
    </div>
    <div id="edit_sale_item_modal" tabindex="-1" class="modal">
        <form method="POST" id="editSaleItemForm" name="editSaleItemForm" action="{{ route('edit_sale_item') }}">
            <div class="modal-content">
                <h4>{{ __('Editar Item') }}</h4>
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m4 l4">
                        <label for="discount" class="black-text">{{ __('Quantidade') }}</label>
                        <input required id="quantity" type="text" class="black-text" name="quantity"
                            value="{{ old('quantity') }}" autofocus>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <label for="discount" class="black-text">{{ __('Desconto (%)') }}</label>
                        <input required id="discount" type="text" class="black-text" name="discount"
                            value="{{ old('discount') }}" autofocus>
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
    <div id="remove_sale_item_modal" tabindex="-1" class="modal">
        <form method="POST" id="removeSaleItemForm" name="removeSaleItemForm" action="{{ route('remove_sale_item') }}">
            <div class="modal-content">
                <h4>{{ __('Remover Item') }}</h4>
                <p>{{ __('Tem certeza que deseja remover este item?') }}</p>
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
        function editSaleItem(button, id, quantity, discount) {
            var tr = button.parentElement.parentElement;
            editSaleItemForm.id.value = id;
            editSaleItemForm.quantity.value = quantity;
            editSaleItemForm.discount.value = discount;
        }

        function removeSaleItem(button, id) {
            var tr = button.parentElement.parentElement;
            removeSaleItemForm.id.value = id;
            removeSaleItemForm.product.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
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
                    html: '{{ session('sale_notification') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
