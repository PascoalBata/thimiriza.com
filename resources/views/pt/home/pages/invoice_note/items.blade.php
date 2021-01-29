<div class="row" id="invoice_note_items_table" style="display: block;">
    <div class="col s12 m12 l12" style="overflow-x: auto;">
        <table class="highlight" style="width: 100%;">
            <thead>
                <tr>
                    <th>{{ __('Nome') }}</th>
                    <th style="text-align: center;">{{ __('Descrição') }}</th>
                    <th style="text-align: center;">{{ __('Tipo') }}</th>
                    <th style="text-align: center;">{{ __('Nota') }}</th>
                    <th style="text-align: right;">{{ __('Preço') }}</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach ($temp_notes as $item)
                @php
                    $product_service = explode('===', $item->name_description);
                    $name = $product_service[0];
                    $description = $product_service[1];
                @endphp
                    <tr>
                        <td>{{ $name . ' ' . $description }}</td>
                        <td style="text-align: center;">{{ $item->description }}</td>
                        @if ($item->type_product_service === 'PRODUCT')
                            <td style="text-align: center;">{{ __('Produto') }}</td>
                        @endif
                        @if ($item->type_product_service === 'SERVICE')
                            <td style="text-align: center;">{{ __('Serviço') }}</td>
                        @endif
                        @if ($item->type === 'CREDIT')
                            <td style="text-align: center;">{{ __('CRÉDITO') }}</td>
                        @endif
                        @if ($item->type === 'DEBIT')
                            <td style="text-align: center;">{{ __('DÉBITO') }}</td>
                        @endif
                        <td style="text-align: right;">{{ number_format($item->value, 2, ',', '.') }}{{ __('MT') }}
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" action="{{route('remove_note_item', $item->id)}}">
                                @csrf
                                @method('DELETE')
                                <button style="width: 100%;" type="submit"
                                    class="waves-effect waves-light btn-small red darken-3"
                                    onclick="return confirm('Tem certeza que deseja remover este item?')">remover</button>
                            </form>
                        </td>
                    </tr>
                    @php
                    $total = $total + $item->value;
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
<div class="row">
    <div class="col s12 m12 l12">
        <a type="button" href="{{ route('store_invoice_note') }}" class="waves-effect waves-light btn-small">
            {{ __('Salvar') }}
            <i class="material-icons right">archive</i>
        </a>
        <a type="button" href="{{ route('clean_temp_note') }}" class="waves-effect waves-light btn-small">
            {{ __('Limpar') }}
            <i class="material-icons right"></i>
        </a>
    </div>
</div>
