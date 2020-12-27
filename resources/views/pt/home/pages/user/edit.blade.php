<div id="edit_user_modal" tabindex="-1" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>{{ __('Actualizar Utilizador') }}</h4>
            <form method="POST" id="editUserNameForm" name="editUserNameForm" action="{{ route('update_user_name', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m4 l4">
                        <label for="edit_name" class="black-text">{{ __('Nome') }}</label>
                        <input required id="edit_name" type="text" class="black-text" name="edit_name" value="{{ $selected_user->name }}"
                            autofocus>
                    </div>
                    <div class="input-field col s12 m3 l3">
                        <label for="edit_surname" class="black-text">{{ __('Apelido') }}</label>
                        <input required id="edit_surname" type="text" class="black-text" name="edit_surname"
                            value="{{ $selected_user->surname }}" autofocus>
                    </div>
                    <div class="input-field col s12 m3 l3">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserEmailForm" name="editUserEmailForm" action="{{ route('update_user_email', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="edit_email" class="black-text">{{ __('Email') }}</label>
                        <input required id="edit_email" type="email" class="black-text" name="edit_email" value="{{ $selected_user->email }}"
                            autofocus>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserPhoneForm" name="editUserPhoneForm" action="{{ route('update_user_phone', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="edit_phone" class="black-text">{{ __('Telefone') }}</label>
                        <input required id="edit_phone" type="number" class="black-text" name="update_phone" value="{{ $selected_user->phone }}"
                            autofocus>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserBirthdateForm" name="editUserBirthdateForm"
                action="{{ route('update_user_birthdate', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="edit_birthdate" class="black-text">{{ __('Data de nascimento') }}</label>
                        <input id="edit_birthdate" type="date" class="black-text" name="edit_birthdate" value="{{ $selected_user->birthdate }}"
                            required>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserAddressForm" name="editUserAddressForm"
                action="{{ route('update_user_address', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="edit_address" class="black-text">{{ __('Endereço') }}</label>
                        <input required id="edit_address" type="text" class="black-text" name="edit_address"
                            value="{{ $selected_user->address }}" autofocus>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserGenderForm" name="editUserGenderForm" action="{{ route('update_user_gender', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m3 l2">
                        <label>{{ $selected_user->gender }}</label>
                    </div>
                    <div class="input-field col s12 m4 l5">
                        <select id="edit_gender" name="edit_gender">
                            <optgroup label="{{ __('Género') }}">
                                <option value="HOMEM">Homem</option>
                                <option value="MULHER">Mulher</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserPrivilegeForm" name="editUserPrivilegeForm"
                action="{{ route('update_user_privilege', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m3 l2">
                        <label>{{ $selected_user->privilege }}</label>
                    </div>
                    <div class="input-field col s12 m4 l5">
                        <select id="edit_privilege" name="edit_privilege">
                            <optgroup label="{{ __('Previlégio') }}">
                                <option value="TOTAL">{{ __('TOTAL') }}</option>
                                <option value="PARCIAL">{{ __('PARCIAL') }}</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserPasswordForm" name="editUserPasswordForm" action="{{ route('update_user_password', $selected_user->id) }}">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="input-field col s12 m4 l4">
                        <label for="edit_name" class="black-text">{{ __('Alteração da senha') }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m8 l8">
                        <label for="edit_actual_password" class="black-text">{{ __('Senha actual') }}</label>
                        <input required id="edit_actual_password" type="password" class="black-text" name="edit_actual_password">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m4 l4">
                        <label for="edit_new_password" class="black-text">{{ __('Nova senha') }}</label>
                        <input required id="edit_new_password" type="password" class="black-text" name="edit_new_password">
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <label for="edit_confirm_password" class="black-text">{{ __('Confirme a senha') }}</label>
                        <input required id="edit_confirm_password" type="password" class="black-text" name="edit_confirm_password">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m4 l4">
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
