@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
    @php
    $sale_type = 'PRODUCT';
    @endphp
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Créditos') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <form method="POST" name="saleForm" action="{{ route('store_sale') }}">
                @method('POST')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="discount" class="black-text active">{{ __('A partir de') }}</label>
                        <input id="discount" placeholder="" type="date" class="black-text" name="discount" value="{{ old('discount') }}">
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="discount" class="black-text active">{{ __('Até') }}</label>
                        <input id="discount" placeholder="" type="date" class="black-text" name="discount" value="{{ old('discount') }}">
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
                        <th>{{ __('Data') }}</th>
                        <th style="text-align: center;">{{ __('Factura') }}</th>
                        <th style="text-align: center;">{{ __('Cliente') }}</th>
                        <th style="text-align: right;">{{ __('Valor') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    $facturas = 0;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->created_at }}</td>
                            <td style="text-align: center;">{{ $invoice->code, 10, 11 }}</td>
                            <td style="text-align: center;">{{ $invoice->client_name }}</td>
                            <td style="text-align: right;">{{ number_format($invoice->price, 2, ',', '.') }}{{ __('MT') }}</td>
                            <td style="text-align: right;">
                                <a class="modal-trigger waves-effect waves-light btn-small" href="#edit_sale_item_modal"
                                    onclick="payInvoice(this, {{ $invoice->id }})">{{__('ver')}}</a>
                            </td>
                        </tr>
                        @php
                        $facturas = $facturas + 1;
                        $total = $total + $invoice->price;
                        @endphp
                    @endforeach
                    <tr>
                        <td style="text-align: left; font-weight: bold;">{{ __('TOTAL') }}</td>
                        <td style="text-align: center; font-weight: bold;">{{$facturas}}</td>
                        <td></td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($total, 2, ',', '.') }}{{ __('MT') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    
@endsection
@section('script')
    <script>
        function payInvoice(button, id) {
            var tr = button.parentElement.parentElement;
            editSaleItemForm.id.value = id;
        }

        function removeSaleItem(button, id) {
            var tr = button.parentElement.parentElement;
            removeSaleItemForm.id.value = id;
            removeSaleItemForm.product.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if (session('credit_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('credit_notification') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
