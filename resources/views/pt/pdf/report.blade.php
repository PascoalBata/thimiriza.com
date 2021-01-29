<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório</title>
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
                bottom: -40px;
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
        @if ($type === 'DEBIT')
            <h3>Relatório (Débito)</h3>
        @endif
        @if ($type === 'CREDIT')
            <h3>Relatório (Crébito)</h3>
        @endif
        @if ($type === 'REPORT')
            <h3>Relatório (Geral)</h3>
        @endif
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
            <tr><th style="text-align:left;">DATA</th>
                <th style="text-align:center;">FACTURA</th>
                @if ($type === 'REPORT')
                <th style="text-align:center;">ESTADO</th>
                @endif
                <th style="text-align:center;">CLIENTE</th>
                <th style="text-align:right;">VALOR</th>
            </tr>
            @foreach ($items as $item)
            <tr>
                <td>{{$item->created_at}}</td>
                <td style="text-align: center;">{{$item->number}}</td>
                @if ($type === 'REPORT')
                    @if ($item->status === 'PAID')
                        <td style="text-align: center;">PAGO</td>
                    @endif
                    @if ($item->status === 'NOT PAID')
                        <td style="text-align: center;">EM DÍVIDA</td>
                    @endif
                @endif
                <td style="text-align: center;">{{$item->client_name}}</td>
                <td style="text-align: right;">{{number_format($item->price, 2, ",", ".") . ' MT'}}</td>
            </tr>
            @endforeach
        </table>
    </main>
    <footer>

    </footer>
</body>
</html>
