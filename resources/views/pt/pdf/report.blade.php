<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotação</title>
    <style>
        #items_table{
            width: 100%;
            font-size: 14px;
            border-top: solid;
            border-top-color: gray;
            border-top-width: medium;
            border-spacing: 0px;
        }
        #items_table td{
                    font-size: 14px;
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
                top: -60px;
                left: 5px;
                right: 0px;
                height: 50px;

                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(66, 66, 66);
                line-height: 30px;
            }

            footer {
                position: fixed;
                bottom: 0px;
                left: 0px;
                right: 0px;
                height: 4cm;

                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(0, 0, 0);
            }

            #table_footer {
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
    </header>

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
            <td style="height: 3cm;"></td><td></td><td></td>
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

    <br/>
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
            <td style="text-align: center;">{{$item->id}}</td>
            @if ($type === 'REPORT')
            <td style="text-align: center;">{{$item->status}}</td>
            @endif
            <td style="text-align: center;">{{$item->client_name}}</td>
            <td style="text-align: right;">{{number_format($item->price, 2, ",", ".") . ' MT'}}</td>
        </tr>
        @endforeach
    </table>

    <footer>
        <table id="table_footer">

        </table>
    </footer>
</body>
</html>
