@extends('pt.home.layouts.app')

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
                <h1 class="display-4 black-text"><strong>{{ __('Facturas Em Dívida') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <form method="POST" name="saleForm" action="{{ route('get_debit') }}">
                @method('POST')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="inicial_date" class="black-text active">{{ __('A partir de') }}</label>
                        <input id="inicial_date" placeholder="" type="date" class="black-text" name="inicial_date" value="{{ old('inicial_date') }}">
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="final_date" class="black-text active">{{ __('Até') }}</label>
                        <input id="final_date" placeholder="" type="date" class="black-text" name="final_date" value="{{ old('final_date') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <button type="submit" class="waves-effect waves-light btn-small">
                            {{ __('Seguir') }}
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
                        <th style=""></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    $facturas = 0;
                    $limit_date = $invoices[0]->created_at;
                    $inicial_date = $invoices[0]->created_at;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $inicial_date = $invoice->created_at }}</td>
                            <td style="text-align: center;">{{ $invoice->code }}</td>
                            <td style="text-align: center;">{{ $invoice->client_name }}</td>
                            <td style="text-align: right;">{{ number_format($invoice->price, 2, ',', '.') }}{{ __('MT') }}</td>
                            <td style="text-align: right;">
                                <a style="width: 25%;" class="modal-trigger waves-effect waves-light btn-small" href="#invoice_payment_modal"
                                    onclick="payInvoice(this, {{ $invoice->id }})">{{__('Pagar')}}</a>
                                    <a class="modal-trigger waves-effect waves-light btn-small" href=""
                                    onclick="window.open('debit/{{ $invoice->id }}');">
                                    {{__('ver')}}</a>
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
            <div class="row">
                <div class="col s12 m12 l12">
                    <a class="modal-trigger waves-effect waves-light btn-small" href=""
                        onclick="window.open('debit/print/{{ strtotime($inicial_date) . strtotime($limit_date) }}');">
                        {{ ('Imprimir') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div id="invoice_payment_modal" tabindex="-1" class="modal">
        <form method="POST" id="payInvoiceForm" name="payInvoiceForm" action="{{ route('invoice_payment') }}">
            <div class="modal-content">
                <h4>{{ __('Pagar Factura')}}</h4>
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m4 l4">
                        <label for="invoice" class="black-text active">{{ __('Factura') }}</label>
                        <input id="invoice" type="text" class="black-text" name="invoice" value="{{ old('invoice') }}" autofocus disabled>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <label for="invoice" class="black-text active">{{ __('Preço') }}</label>
                        <input id="price" type="text" class="black-text" name="price" value="{{ old('price') }}" autofocus disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="waves-effect waves-light btn-small " >
                    {{ __('PAGAR') }}
                    <i class="material-icons left"></i>
                </button>
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('CANCELAR') }}</a>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        function payInvoice(button, id) {
            var tr = button.parentElement.parentElement;
            payInvoiceForm.id.value = id;
            payInvoiceForm.invoice.value = tr.cells[1].innerHTML;
            payInvoiceForm.price.value = tr.cells[3].innerHTML;
        }

        function removeSaleItem(button, id) {
            //var tr = button.parentElement.parentElement;
            //removeSaleItemForm.id.value = id;
            //removeSaleItemForm.product.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if (session('debit_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('debit_notification') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
