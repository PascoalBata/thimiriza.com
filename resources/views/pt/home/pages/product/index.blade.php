<div id="products_table_modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>{{ __('Produtos') }}</h4>
        <div class="row" id="products_table" style="display: block;">
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
                                    <label class="black-text">
                                        <input disabled type="checkbox" class="filled-in"
                                        @if ($product->iva === 'on')
                                            checked
                                        @endif
                                        />
                                        <span></span>
                                    </label>
                                </td>
                                <td style="text-align: center;">{{ number_format($product->price, 2, ',', '.') }}
                                    {{ __('MT') }}</td>
                                <td style="text-align: right;">
                                    <a style="width: 100%;" class="waves-effect waves-light btn-small"
                                        href="{{ route('edit_product', $product->id) }}">editar</a>
                                    <form method="POST" action="{{ route('destroy_product', $product->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button style="width: 100%;" type="submit"
                                            class="waves-effect waves-light btn-small red darken-3">remover</button>
                                    </form>
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
