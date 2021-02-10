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
                <h1 class="display-4 black-text"><strong>{{ __('Relatório de Impostos') }}</strong></h1>
                @if ($company_type === 'NORMAL')
                    <h5 class="display-4 black-text"><strong>{{ __('Regime Normal') }}</strong></h5>
                @endif
                @if ($company_type === 'ISPC')
                    <h5 class="display-4 black-text"><strong>{{ __('Regime ISPC') }}</strong></h5>
                @endif
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <form method="POST" name="saleForm" action="{{ route('get_report') }}">
                @method('POST')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="inicial_date" class="black-text active">{{ __('A partir de') }}</label>
                        <input id="inicial_date" placeholder="" type="date" class="black-text" name="inicial_date"
                            value="{{ old('inicial_date') }}">
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="final_date" class="black-text active">{{ __('Até') }}</label>
                        <input id="final_date" placeholder="" type="date" class="black-text" name="final_date"
                            value="{{ old('final_date') }}">
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
                        <th>{{ __('Factura') }}</th>
                        <th style="text-align: right;">{{ __('Incidência') }}</th>
                        @if ($company_type === 'NORMAL')
                            <th style="text-align: right;">{{ __('Imposto (17%)') }}</th>
                        @endif
                        @if ($company_type === 'ISPC')
                            <th style="text-align: right;">{{ __('Imposto (3%)') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($invoices) > 0)
                    @php
                    $total_iva = 0;
                    $total_incident = 0;
                    $total_invoices = 0;
                    $limit_date = $invoices[0]->created_at;
                    $inicial_date = $invoices[0]->created_at;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ date('Y', strtotime($invoice->created_at)) . '/' . $invoice->number }}</td>
                            <td style="text-align: right;">{{ number_format($invoice->incident, 2, ',', '.') }} {{__('MT') }}</td>
                            @if ($company_type === 'NORMAL')
                                <td style="text-align: right;">{{ number_format($invoice->iva, 2, ',', '.') }} {{__('MT') }}</td>
                            @endif
                            @if ($company_type === 'ISPC')
                                <td style="text-align: right;">{{ number_format($invoice->incident * 0.03, 2, ',', '.') }} {{__('MT') }}</td>
                            @endif
                            <td style="text-align: right;">
                                <a class="modal-trigger waves-effect waves-light btn-small" href="{{route('report_invoice', $invoice->id)}}">
                                    {{ __('ver') }}</a>
                            </td>
                        </tr>
                        @php
                        $total_invoices = $total_invoices + 1;
                        $total_incident = $total_incident + $invoice->incident;
                        if ($company_type === 'NORMAL') {
                            $total_iva = $total_iva + $invoice->iva;
                        }
                        if ($company_type === 'ISPC') {
                            $total_iva = $total_iva + $invoice->incident * 0.03;
                        }
                        @endphp
                    @endforeach
                    @php
                    if($total_invoices > 0){
                        $inicial_date = $invoices[$total_invoices-1]->created_at;
                    }
                    @endphp
                    <tr>
                        <td style="text-align: left; font-weight: bold;">{{ __('TOTAL (') . $total_invoices . ')'}}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($total_incident, 2, ',', '.') }} {{__('MT') }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($total_iva, 2, ',', '.') }} {{__('MT') }}</td>
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col s12 m12 l12">
                    @if (count($invoices) > 0)
                        <a class="modal-trigger waves-effect waves-light btn-small"
                        href="{{ route('print_tax',strtotime($inicial_date)  . strtotime($limit_date)) }}">
                            {{ ('Imprimir') }}</a>
                    @endif
                </div>
            </div>
            {{ $invoices->links() }}
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if (session('credit_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('
                    credit_notification ') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
