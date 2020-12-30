<div id="edit_sale_item_modal" tabindex="-1" class="modal">
    <form method="POST" id="editSaleItemForm" name="editSaleItemForm" action="{{ route('edit_sale_item') }}">
        <div class="modal-content">
            <h4>{{ __('Editar Item') }}</h4>
            @method('PUT')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m4 l4">
                    <label for="discount" class="black-text">{{ __('Quantidade') }}</label>
                    <input required id="quantity" type="text" class="black-text" name="quantity"
                        value="{{ old('quantity') }}" autofocus>
                </div>
                <div class="input-field col s12 m4 l4">
                    <label for="discount" class="black-text">{{ __('Desconto (%)') }}</label>
                    <input required id="discount" type="text" class="black-text" name="discount"
                        value="{{ old('discount') }}" autofocus>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="waves-effect waves-light btn-small ">
                {{ __('SALVAR') }}
                <i class="material-icons left"></i>
            </button>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('FECHAR') }}</a>
        </div>
    </form>
</div>
