<div class="row" id="service_table" style="display: block;">
    <div class="col s12 m12 l12" style="overflow-x: auto;">
        <table class="highlight" style="width: 100%;">
            <thead>
                <tr>
                    <th>{{ __('Factura') }}</th>
                    <th style="text-align: center;">{{ __('Cliente') }}</th>
                    <th style="text-align: center;">{{ __('Tipo') }}</th>
                    <th style="text-align: right;">{{ __('Preço') }}</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notes_data as $note_data)
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
