<div id="table_products_modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>{{ __('Produtos') }}</h4>
        <div class="row" id="service_table" style="display: block;">
            <div class="col s12 m12 l12">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Nome') }}</th>
                            <th style="text-align: center;">{{ __('Descrição') }}</th>
                            <th style="text-align: center;">{{ __('Quantidade') }}</th>
                            <th style="text-align: center;">{{ __('IVA') }}</th>
                            <th style="text-align: center;">{{ __('Preço') }}</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td style="text-align: center;">{{ $product->description }}</td>
                                <td style="text-align: center;">{{ $product->quantity }}</td>
                                <td style="text-align: center;">
                                    @if ($product->iva === 'on')
                                    <label class="black-text">
                                        <input disabled type="checkbox" class="filled-in" checked id="product_iva" name="product_iva"/>
                                        <span></span>
                                    </label>
                                    @else
                                    <label class="black-text">
                                        <input disabled type="checkbox" class="filled-in" id="product_iva" name="product_iva"/>
                                        <span></span>
                                    </label>
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ number_format($product->price, 2, ',', '.') }}
                                    {{ __('MT') }}</td>
                                <td style="text-align: right;">
                                    <a style="width: 100%;" class="modal-trigger waves-effect waves-light btn-small"
                                        href="{{ route('edit_product', $product->id) }}">editar</a>
                                    <a style="width: 100%;"
                                        class="modal-trigger waves-effect waves-light btn-small red darken-3"
                                        href="#destroy_product_modal">remover</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $products->links() !!}
            </div>
        </div>
    </div>
</div>
