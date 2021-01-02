<div id="edit_note_modal" tabindex="-1" class="modal">
    <form method="POST" id="editNoteForm" name="editNoteForm" action="{{ route('update_invoice_note', $selected_note->id) }}">
        <div class="modal-content">
            <h4>{{ __('Editar Nota') }}</h4>
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m2 l2">
                    <label for="edit_invoice_id" class="black-text">
                        Actual:
                        @if ($selected_note->type === 'CREDIT')
                            Crédito
                        @endif
                        @if ($selected_note->type === 'DEDIT')
                            Dédito
                        @endif
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <select id="edit_type" name="edit_type">
                        <optgroup label="{{ __('Tipo de nota') }}">
                            <option value="CREDIT">{{ __('Crédito') }}</option>
                            <option value="DEBIT">{{ __('Dédito') }}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="edit_invoice_id" class="black-text">{{ __('ID da factura') }}</label>
                    <input id="edit_invoice_id" type="number" autocomplete="off" class="black-text" name="edit_invoice_id"
                    value="{{ $selected_note->id_invoice }}" required/>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <textarea id="edit_description" name="edit_description" class="materialize-textarea" data-length="200">{{ $selected_note->description }}</textarea>
                    <label for="edit_description" class="black-text">Descrição</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <label for="edit_value" class="black-text">{{ __('Valor') }}</label>
                    <input id="edit_value" type="number" class="black-text" name="edit_value" value="{{ $selected_note->value }}"
                        required>
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
