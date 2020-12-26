<div id="remove_product_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="removeProductForm" name="removeProductForm" action="{{ route('destroy_product', $selected_product->id) }}">
        <div class="modal-content">
            <h4>{{ __('Remover Produto') }}</h4>
            <p>{{ __('Deseja remover este produto?') }}</p>
            @method('DELETE')
            @csrf
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <input id="product" type="text" class="black-text" name="product"
                    value="{{ '(' . $selected_product->id . ')' . $selected_product->name . ' ' . $selected_product->description }}"
                        autofocus disabled>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="waves-effect waves-light btn-small ">
                {{ __('SIM') }}
                <i class="material-icons left"></i>
            </button>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('NÃO') }}</a>
        </div>
    </form>
</div>
