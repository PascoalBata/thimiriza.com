<div id="edit_client_enterprise_modal" tabindex="-1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>{{ __('Actualizar Cliente Empresarial')}}</h4>
        <p></p>
        <form method="POST" id="editClientEnterpriseNameForm" name="editClientEnterpriseNameForm" action="{{ route('edit_client_enterprise_name', $selected_client_enterprise->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                    <input id="name"  type="text" class="black-text" name="name" value="{{ $selected_client_enterprise->name }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                     <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientEnterpriseEmailForm" name="editClientEnterpriseEmailForm" action="{{ route('edit_client_enterprise_email', $selected_client_enterprise->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="email" class="black-text">{{ __('Email') }}</label>
                    <input id="email"  type="email" class="black-text" name="email" value="{{ $selected_client_enterprise->email }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientEnterprisePhoneForm" name="editClientEnterprisePhoneForm" action="{{ route('edit_client_enterprise_phone', $selected_client_enterprise->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input id="phone"  type="text" class="black-text" name="phone" value="{{ $selected_client_enterprise->phone }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientEnterpriseNuitForm" name="editClientEnterpriseNuitForm" action="{{ route('edit_client_enterprise_nuit', $selected_client_enterprise->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                    <input id="nuit"  type="number" class="black-text" name="nuit" value="{{ $selected_client_enterprise->nuit }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientEnterpriseAddressForm" name="editClientEnterpriseAddressForm" action="{{ route('edit_client_enterprise_address', $selected_client_enterprise->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="address" class="black-text">{{ __('Endereço') }}</label>
                    <input id="address"  type="text" class="black-text" name="address" value="{{ $selected_client_enterprise->address }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
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
