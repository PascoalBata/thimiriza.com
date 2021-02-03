<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if ($note->type === 'CREDIT')
            Nota de Crédito
        @endif
        @if ($note->type === 'DEBIT')
            Nota de Drédito
        @endif
    </title>
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
                top: -60px;
                left: 0px;
                right: 0px;
                height: 8cm;

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
                bottom: -20px;
                left: 0px;
                right: 0px;
                height: 3.5cm;

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
        @if ($note->type === 'CREDIT')
            <h3>Nota de Crédito</h3>
        @endif
        @if ($note->type === 'DEBIT')
        <h3>Nota de Drédito</h3>
        @endif
        <table id="table_info" style="width: 100%; font-size: 14px; border-top: solid; border-top-color: gray; border-top-width: medium;">
            <tr>
                <td>
                    <table>
                        <tr><td style="width: 100px;">Data:</td><td> {{$note->created_at}}  </td></tr>
                        @if ($user->privilege !== 'ADMIN')
                        <tr><td style="width: 100px;">Responsável:</td><td colspan="2" style="width: 230px;"> {{$user->name . ' ' . $user->surname}}  </td></tr>
                        @endif
                        @if ($user->privilege === 'ADMIN')
                        <tr><td style="width: 100px;">Responsável:</td><td colspan="2" style="width: 230px;"> {{$user->name}}  </td></tr>
                        @endif
                        <tr><td style="width: 100px;">Factura:</td><td colspan="2" style="width: 230px;"> {{$note->invoice_number}}  </td></tr>
                    </table>
                </td>
                <td>

                </td>
                <td rowspan="2">
                    <table>
                        <tr><td></td></tr>
                        <tr><td><img src= "{{ url('storage/' .$company->logo) }}"  height="130px" width="210px"/></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td><td></td><td></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 14px;">
            <tr><td>DE:</td><td>PARA:</td></tr>
            <tr><td><strong> {{$company->name}} </strong></td>
                <td><strong>
                @if ($client->type === 'SINGULAR')
                    {{$client->name . ' ' . $client->surname}}
                @endif
                @if ($client->type === 'ENTERPRISE')
                    {{$client->name}}
                @endif
            </strong></td></tr>
            <tr><td>NUIT:  {{$company->nuit}} </td><td>NUIT:  {{$client->nuit}} </td></tr>
            <tr><td>Endereço:</td><td>Endereço:</td></tr>
            <tr><td> {{$company->address}} </td><td> {{$client->address}} </td></tr>
        </table>
    </header>
    <main>
        <table id="items_table">
            <tr>
                <th>NOME</th>
                <th style="text-align:center;">TIPO</th>
                <th>DESCRIÇÃO</th>
                <th style="text-align:right;">VALOR</th>
            </tr>
            @foreach ($items as $item)
            <tr>
                <td>
                    {{$item->prduct_service . ' ' . $item->product_service_description}}
                </td>
                <td style="text-align: center">
                    @if ($item->type_product_service === 'PRODUCT')
                        {{__('PRODUTO')}}
                    @endif
                    @if ($item->type_product_service === 'SERVICE')
                        {{__('SERVIÇO')}}
                    @endif
                </td>
                <td style="text-align: center">
                    {{$item->description}}
                </td>
                <td style="text-align: right">
                    {{ number_format($item->value, 2, ',', '.') }}{{ __('MT') }}
                </td>
            </tr>
            @endforeach
        </table>
    </main>

    <footer>
        <table id="table_footer">
            <tr><td colspan="2">Banco: {{ $company->bank_account_name }}</td><td></td><td><strong>Total:</strong></td><td colspan="3" style="text-align: right;"> {{number_format($note->value, 2, ",", ".") . ' MT' }} </td></tr>
            <tr><td colspan="2">Titular: {{ $company->bank_account_owner }}</td><td></td><td><strong></strong></td><td colspan="3" style="text-align: right;"></td></tr>
            <tr><td colspan="2">Nº. da conta: {{ $company->bank_account_number }}</td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td colspan="2">Titular: {{ $company->bank_account_nib }}</td><td></td><td><strong></strong></td><td colspan="3" style="text-align: right;"></td></tr>
            <tr><td><br/></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td colspan="4">Documento processado por computador (Thimiriza)</td><td></td><td></td><td></td><td><strong></strong></td><td></td></tr>
            </table>
    </footer>
</body>
</html>
