<div class="row" style="padding-bottom: 5%">
    <form method="POST" action="{{ route('store_user') }}">
        @csrf
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="name" class="black-text">{{ __('Nome') }}</label>
                <input id="name" type="text" class="black-text" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="surname" class="black-text">{{ __('Apelido') }}</label>
                <input id="surname" type="text" class="black-text" name="surname" value="{{ old('surname') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <select id="gender" name="gender">
                    <optgroup label="{{ __('Género') }}">
                        <option value="HOMEM">Homem</option>
                        <option value="MULHER">Mulher</option>
                    </optgroup>
                </select>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="birthdate" class="black-text">{{ __('Data de nascimento') }}</label>
                <input id="birthdate" placeholder="" type="date" class="black-text" name="birthdate" value="{{ old('birthdate') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="email" class="black-text">{{ __('Email') }}</label>
                <input id="email" type="email" class="black-text" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                <input id="phone" type="tel" data-target="20" class="black-text" name="phone"
                    value="{{ old('phone') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <select id="privilege" name="privilege">
                    <optgroup label="{{ __('Previlégio') }}">
                        <option value="PARCIAL">Parcial</option>
                        <option value="TOTAL">Total</option>
                    </optgroup>
                </select>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="address" class="black-text">{{ __('Endereço/Morada') }}</label>
                <input id="address" type="text" class="black-text" name="address" value="{{ old('address') }}"
                    required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="password" class="black-text">{{ __('Senha') }}</label>
                <input id="password" type="password" class="black-text" name="password"
                    value="{{ old('password') }}" required>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="confirm_password" class="black-text">{{ __('Confirme a senha') }}</label>
                <input id="confirm_password" type="password" class="black-text" name="confirm_password"
                    value="{{ old('confirm_password') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <button type="submit" class="waves-effect waves-light btn-small" style="margin-right: 0.5%; margin-top: 0.5%; min-width: 110px;">
                    {{ __('Salvar') }}
                    <i class="material-icons right">archive</i>
                </button>
                <button type="reset" class="waves-effect waves-light btn-small" style="margin-right: 0.5%; margin-top: 0.5%; min-width: 110px;">
                    {{ __('Limpar') }}
                    <i class="material-icons right"></i>
                </button>
                <a class="waves-effect waves-light btn-small modal-trigger" href="#table_users_modal" style="margin-right: 0.5%; margin-top: 0.5%; min-width: 110px;">{{__('Utilizadores')}}</a>
            </div>
        </div>
    </form>
</div>
