<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotação</title>
    <style>
        @page {
                size: 21cm 29.7cm;
                margin: 27mm 16mm 27mm 16mm;
            }
        #items_table{
            width: 100%;
            font-size: 12px;
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
                    font-size: 11px;
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
                top: 7.8cm;
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
        @if ($data['type'] === 'QUOTE')
            <h3>Cotação</h3>
        @endif
        @if ($data['type'] === 'INVOICE')
            <h3>Factura: {{$serie . '/' . $invoice_number}}</h3>
        @endif
        <table id="table_info" style="width: 100%; font-size: 14px; border-top: solid; border-top-color: gray; border-top-width: medium;">
            <tr>
                <td>
                    <table>
                        <tr><td style="width: 100px;">Data:</td><td> {{now()}}  </td></tr>
                        <tr><td style="width: 100px;">Responsável:</td><td colspan="2" style="width: 230px;"> {{$data['user_name']}}  </td></tr>
                    </table>
                </td>
                <td>

                </td>
                <td rowspan="2">
                    <table>
                        <tr><td></td></tr>
                        <tr><td><img src= "{{$data['company_logo'] }}"  height="130px" width="210px"/></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td><td></td><td></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 14px;">
            <tr><td>DE:</td><td>PARA:</td></tr>
            <tr><td><strong> {{$data['company_name']}} </strong></td><td><strong> {{$data['client_name']}} </strong></td></tr>
            <tr><td>NUIT:  {{$data['company_nuit']}} </td><td>NUIT:  {{$data['client_nuit']}} </td></tr>
            <tr><td>Endereço:</td><td>Endereço:</td></tr>
            <tr><td> {{$data['company_address']}} </td><td> {{$data['client_address']}} </td></tr>
        </table>
    </header>
    <main>
        <table id="items_table">
            <tr><th style="text-align:left;">NOME</th><th style="text-align:center;">DESCRIÇÃO</th><th style="text-align:center;">QTD</th><th style="text-align:right;">PREÇO UNIT.</th><th style="text-align:center;">IVA</th><th style="text-align:center;">DESC.</th><th style="text-align:right;">PREÇO INC.</th><th style="text-align:right;">PREÇO TOTAL</th></tr>
            @foreach ($sale_items as $sale_item)
                <tr>
                    <td>
                        {{ $sale_item['name'] }}
                    </td>
                    <td style="text-align:center;">
                        {{ $sale_item['description'] }}
                    </td>
                    <td style="text-align:center;">
                        {{ $sale_item['quantity'] }}
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($sale_item['price'], 2, ",", ".") . ' MT' }}
                    </td>
                    <td style="text-align:center;">
                        {{ $sale_item['iva'] . '%' }}
                    </td>
                    <td style="text-align:center;">
                        {{ $sale_item['discount'] . '%' }}
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($sale_item['price_incident'], 2, ",", ".") . ' MT' }}
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($sale_item['price_sale'], 2, ",", ".") . ' MT' }}
                    </td>
                </tr>
            @endforeach
        </table>
    </main>

    <footer>
        <table id="table_footer">
            <tr><td colspan="2">Banco: {{ $data['company_bank_account_name'] }}</td><td></td><td><strong>Incidência:</strong></td><td colspan="3" style="text-align: right;"> {{number_format($price_incident_total, 2, ",", ".") . ' MT' }} </td></tr>
            <tr><td colspan="2">Titular: {{ $data['company_bank_account_owner'] }}</td><td></td><td><strong>IVA:</strong></td><td colspan="3" style="text-align: right;"> {{number_format($iva_total, 2, ",", ".") . ' MT' }} </td></tr>
            <tr><td colspan="2">Nº. da conta: {{ $data['company_bank_account_number'] }}</td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td colspan="2">Titular: {{ $data['company_bank_account_nib'] }}</td><td></td><td><strong>Total:</strong></td><td colspan="3" style="text-align: right;"> {{number_format($price_total, 2, ",", ".") . ' MT' }} </td></tr>
            <tr><td><br/></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td colspan="4">Documento processado por computador (Thimiriza)</td><td></td><td></td><td></td><td><strong></strong></td><td></td></tr>
            </table>
    </footer>
</body>
</html>
