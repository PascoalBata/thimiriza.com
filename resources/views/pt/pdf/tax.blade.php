<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Impostos</title>
    <style>
        @page {
                size: 21cm 29.7cm;
                margin: 27mm 16mm 27mm 16mm;
            }
        #items_table{
            width: 100%;
            font-size: 14px;
            border-top: solid;
            border-top-color: gray;
            border-top-width: medium;
            border-spacing: 0px;
        }
        #items_table td{
                    font-size: 12px;
                    border-top: solid;
                border-top-color: transparent;
                border-top-width: thick;
                }
        #items_table th{
                    border-bottom: solid;
                    border-bottom-color: gray;
                    border-bottom-width: medium;
                    font-size: 12px;
                }
            header {
                position: fixed;
                top: -80px;
                left: 0px;
                right: 0px;
                height: 8.5cm;

                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(66, 66, 66);
                line-height: 20px;
            }
            main{
                top: 4.5cm;
                position: relative;
                page-break-after: auto;
            }
            main:last-child {
                page-break-after: never;
            }
            footer {
                position: fixed;
                bottom: -100px;
                left: 0px;
                right: 0px;
                height: 4cm;

                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(0, 0, 0);
            }

            footer #table_footer {
                width: 100%;
                border-top: solid;
                border-top-color: gray;
                border-top-width: medium;
            }
    </style>
</head>
<body>
    <header>
        <h3>Relatório (Impostos)</h3>

        <table style="width: 100%; font-size: 14px; border-top: solid; border-top-color: gray; border-top-width: medium;">
            <tr>
                <td>
                    <table>
                        <tr><td style="width: 100px;">Emitido a:</td><td> {{now()}}  </td></tr>
                        <tr><td style="width: 100px;">Por:</td><td colspan="2" style="width: 230px;">
                            @if ($user->privileges === 'ADMIN')
                                {{$user->name}}
                            @else
                                {{$user->name . ' ' . $user->surname}}
                            @endif
                        </td></tr>
                    </table>
                </td>
                <td>

                </td>
                <td rowspan="2">
                    <table>
                        <tr><td></td></tr>
                        <tr><td><img src= "{{url('storage/' .$company->logo) }}"  height="130px" width="210px"/></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="height: 1.5cm;"></td><td></td><td></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; font-size: 16px;">
                    @if (date('Y-m-d', strtotime($inicial_date)) === date('Y-m-d', strtotime($limit_date)))
                    {{date('d-m-Y', strtotime($inicial_date))}}
                    @else
                    {{date('d-m-Y', strtotime($inicial_date))}} até {{date('d-m-Y', strtotime($limit_date))}}
                    @endif
                </td>
            </tr>
        </table>
    </header>
    <main>
        <table id="items_table">
            <thead>
                <tr>
                <th style="text-align:left;">FACTURA</th>
                <th style="text-align:right;">INCIDÊNCIA</th>
                @if ($company->type === 'NORMAL')
                    <th style="text-align:right;">IMPOSTO (17%)</th>
                @endif
                @if ($company->type === 'ISPC')
                    <th style="text-align:right;">IMPOSTO (3%)</th>
                @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $total_invoices = 0;
                    $total_incident = 0;
                    $total_iva = 0;
                @endphp
            @foreach ($items as $item)
                @php
                    $incident = $item->price - $item->iva + $item->discount;
                @endphp
                <tr>
                    <td>{{ date('Y', strtotime($item->created_at)) . '/' . $item->number }}</td>
                    <td style="text-align: right;">{{ number_format($incident, 2, ',', '.') }} {{__('MT') }}</td>
                    @if ($company->type === 'NORMAL')
                        <td style="text-align: right;">{{ number_format($item->iva, 2, ',', '.') }} {{__('MT') }}</td>
                    @endif
                    @if ($company->type === 'ISPC')
                        <td style="text-align: right;">{{ number_format( $incident * 0.03, 2, ',', '.') }} {{__('MT') }}</td>
                    @endif
                </tr>
                @php
                    $total_invoices = $total_invoices + 1;
                    $total_incident = $total_incident + $incident;
                    if($company->type === 'NORMAL'){
                        $total_iva = $total_iva + $item->iva;
                    }
                    if($company->type === 'ISPC'){
                        $total_iva = $total_iva + ($incident * 0.03);
                    }
                @endphp
            @endforeach
            </tbody>
        </table>
    </main>
    <footer>
        <table id="table_footer">
            <tr><td><strong>TOTAL ({{ $total_invoices }})</strong></td><td style="text-align: right"><strong>{{ number_format( $total_incident, 2, ',', '.') }} {{__('MT') }}</strong></td><td style="text-align: right"><strong>{{ number_format( $total_iva, 2, ',', '.') }} {{__('MT') }}</strong></td></tr>
            <tr><td></td><td></td><td></td><td></td></tr>
            <tr><td colspan="3">Documento processado por computador (Thimiriza)</td></tr>
        </table>
    </footer>
</body>
</html>
