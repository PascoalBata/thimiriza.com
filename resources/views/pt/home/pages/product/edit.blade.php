<div id="edit_product_modal" tabindex="-1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>{{ __('Actualizar Produto') }}</h4>
        <p>Altere somente os campos que pretende actualizar.</p>
        <form method="POST" id="editProductForm" name="editProductForm" action="{{ route('update_product', $selected_product->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m5 l5">
                    <label for="edit_name" class="black-text">{{ __('Nome') }}</label>
                    <input id="edit_name" type="text" class="black-text" name="edit_name" value="{{ $selected_product->name }}" autofocus>
                </div>
                <div class="input-field col s12 m5 l5">
                    <label for="edit_description" class="black-text">{{ __('Descrição') }}</label>
                    <input id="edit_description" type="text" class="black-text" name="edit_description"
                        value="{{ $selected_product->description }}" autofocus>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m5 l5">
                    <label for="edit_quantity" class="black-text">{{ __('Quantidade') }}</label>
                    <input id="edit_quantity" type="number" class="black-text" name="edit_quantity" value="{{ $selected_product->quantity }}"
                        autofocus>
                </div>
                <div class="input-field col s12 m5 l5">
                    <label for="edit_price" class="black-text">{{ __('Preço') }}</label>
                    <input id="edit_price" type="number" class="black-text" name="edit_price" value="{{ $selected_product->price }}"
                        autofocus>
                </div>
            </div>
            <div class="row">
                @if ($company_type === 'NORMAL')
                    <div class="input-field col s12 m6 l6">
                        <p>
                            @if ($selected_product->iva === 'on')
                                <label class="black-text">
                                    <input type="checkbox" checked id="edit_product_iva" name="edit_product_iva" />
                                    <span>{{ __('Incluir IVA') }}</span>
                                </label>
                            @else
                                <label class="black-text">
                                    <input type="checkbox" id="edit_product_iva" name="edit_product_iva" />
                                    <span>{{ __('Incluir IVA') }}</span>
                                </label>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="input-field col s12 m5 l5">
                    <button type="submit" class="waves-effect waves-light btn-small ">
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fechar</a>
    </div>
</div>
