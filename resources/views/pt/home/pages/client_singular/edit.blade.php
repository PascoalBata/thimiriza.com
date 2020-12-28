<div id="edit_client_singular_modal" tabindex="-1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>{{ __('Actualizar Cliente Empresarial')}}</h4>
        <p></p>
        <form method="POST" id="editClientSingularNameForm" name="editClientSingularNameForm" action="{{ route('edit_client_singular_name', $selected_client_singular->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m3 l3">
                    <label for="name" class="black-text">{{ __('Nome') }}</label>
                    <input required id="name"  type="text" class="black-text" name="name" value="{{ $selected_client_singular->name }}" autofocus>
                </div>
                <div class="input-field col s12 m3 l3">
                    <label for="surname" class="black-text">{{ __('Apeldio') }}</label>
                    <input required id="surname"  type="text" class="black-text" name="surname" value="{{ $selected_client_singular->surname }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                     <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularEmailForm" name="editClientSingularEmailForm" action="{{ route('edit_client_singular_email', $selected_client_singular->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="email" class="black-text">{{ __('Email') }}</label>
                    <input required id="email"  type="email" class="black-text" name="email" value="{{ $selected_client_singular->email }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularPhoneForm" name="editClientSingularPhoneForm" action="{{ route('edit_client_singular_phone', $selected_client_singular->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                    <input required id="phone"  type="text" class="black-text" name="phone" value="{{ $selected_client_singular->phone }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularNuitForm" name="editClientSingularNuitForm" action="{{ route('edit_client_singular_nuit', $selected_client_singular->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                    <input required id="nuit"  type="number" class="black-text" name="nuit" value="{{ $selected_client_singular->nuit }}" autofocus>
                </div>
                <div class="input-field col s12 m6 l6">
                    <button type="submit" class="waves-effect waves-light btn-small " >
                        {{ __('Salvar') }}
                        <i class="material-icons left"></i>
                    </button>
                </div>
            </div>
        </form>
        <form method="POST" id="editClientSingularAddressForm" name="editClientSingularAddressForm" action="{{ route('edit_client_singular_address', $selected_client_singular->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <label for="address" class="black-text">{{ __('Endereço') }}</label>
                    <input required id="address"  type="text" class="black-text" name="address" value="{{ $selected_client_singular->address }}" autofocus>
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
