<div class="row" style="padding-bottom: 5%">
    <form method="POST" name="saleForm" action="{{ route('store_invoice_note') }}">
        @method('POST')
        @csrf
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="invoice_id" class="black-text">{{ __('ID da factura') }}</label>
                <input id="invoice_id" type="number" autocomplete="off" class="black-text" name="id_invoice"
                value="{{ old('price') }}" required/>
            </div>
            <div class="input-field col s12 m6 l6">
                <select id="type" name="type">
                    <optgroup label="{{ __('Tipo de nota') }}">
                        <option value="CREDIT">{{ __('Crédito') }}</option>
                        <option value="DEBIT">{{ __('Dédito') }}</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <textarea id="description" name="description" class="materialize-textarea" data-length="200"></textarea>
                <label for="description" class="black-text">Descrição</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="value" class="black-text">{{ __('Valor') }}</label>
                <input id="value" type="number" class="black-text" name="value" value="{{ old('price') }}"
                    required>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <button type="submit" class="waves-effect waves-light btn-small">
                    {{ __('Salvar') }}
                    <i class="material-icons right">archive</i>
                </button>
                <button type="reset" class="waves-effect waves-light btn-small">
                    {{ __('Limpar') }}
                    <i class="material-icons right"></i>
                </button>
            </div>
        </div>
    </form>
</div>
