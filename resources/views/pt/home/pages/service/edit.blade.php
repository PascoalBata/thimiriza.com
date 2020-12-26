<div id="edit_service_modal" tabindex="-1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>{{ __('Actualizar Serviço') }}</h4>
        <p>Altere somente os campos que pretende actualizar.</p>
        <form method="POST" id="editServiceForm" name="editServiceForm" action="{{ route('update_service', $selected_service->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m5 l5">
                    <label for="edit_name" class="black-text">{{ __('Nome') }}</label>
                    <input id="edit_name" type="text" class="black-text" name="edit_name" value="{{ $selected_service->name }}" autofocus>
                </div>
                <div class="input-field col s12 m5 l5">
                    <label for="edit_description" class="black-text">{{ __('Descrição') }}</label>
                    <input id="edit_description" type="text" class="black-text" name="edit_description"
                        value="{{ $selected_service->description }}" autofocus>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m5 l5">
                    <label for="edit_price" class="black-text">{{ __('Preço') }}</label>
                    <input id="edit_price" type="number" class="black-text" name="edit_price" value="{{ $selected_service->price }}"
                        autofocus>
                </div>
                @if ($company_type === 'NORMAL')
                    <div class="input-field col s12 m6 l6">
                        <p>
                            <label class="black-text">
                                <input type="checkbox"
                                    @if ($selected_service->iva === 'on')
                                        checked
                                    @endif
                                    id="edit_service_iva" name="edit_service_iva" />
                                <span>{{ __('Incluir IVA') }}</span>
                            </label>
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
