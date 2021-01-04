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

                        </button>
                        <button type="reset" class="waves-effect waves-light btn-small">
                            {{ __('Limpar') }}

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
                    @if (count($invoices) > 0)
                    @php
                    $total = 0;
                    $facturas = 0;
                    $limit_date = $invoices[0]->created_at;
                    $inicial_date = $invoices[0]->created_at;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $inicial_date = $invoice->created_at }}</td>
                            <td style="text-align: center;">{{ $invoice->id }}</td>
                            <td style="text-align: center;">{{ $invoice->client_name }}</td>
                            <td style="text-align: right;">{{ number_format($invoice->price, 2, ',', '.') }}{{ __('MT') }}</td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{route ('pay_invoice', $invoice->id )}}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="waves-effect waves-light btn-small">{{__('Pagar')}}</button>
                                    <a class="waves-effect waves-yellow btn-small"
                                    href="{{ route('debit_invoice', $invoice->id) }}">{{__('ver')}}</a>
                                </form>
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
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col s12 m12 l12">
                    @if (count($invoices) > 0)
                        <a class="waves-effect waves-light btn-small" href="{{route('print_debit', strtotime($inicial_date) . strtotime($limit_date))}}">
                            {{ ('Imprimir') }}</a>
                    @endif
                </div>
            </div>
            {{$invoices->links()}}
        </div>
    </div>
@endsection
@section('script')
    <script>
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
