<div class="row" style="padding-bottom: 5%">
    <form method="POST" action="{{ route('store_client_enterprise') }}">
        @csrf
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <select id="type" name="type" onchange="changeClient(this.value)">
                    <optgroup label="{{__('Tipo')}}">
                        <option value="ENTERPRISE" selected>Empresarial</option>
                        <option value="SINGULAR">Singular</option>
                    </optgroup>
                </select>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="email" class="black-text">{{ __('Email') }}</label>
                <input id="email" type="email" class="black-text" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="name" class="black-text">{{ __('Nome') }}</label>
                <input id="name" type="text" class="black-text" name="name" value="{{ old('name') }}" required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="nuit" class="black-text">{{ __('NUIT') }}</label>
                <input id="nuit" type="number" class="black-text" name="nuit" value="{{ old('nuit') }}" required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                <input id="phone" type="tel" class="black-text" name="phone" value="{{ old('phone') }}" required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="address" class="black-text">{{ __('Endereço') }}</label>
                <input id="address" type="text" class="black-text" name="address" value="{{ old('address') }}" required>
                @error('email')
                    <span class="red-text" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
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
                <a class="waves-effect waves-light btn-small modal-trigger" href="#table_clients_enterprise_modal" style="margin-right: 0.5%; margin-top: 0.5%; min-width: 110px;"
                >{{__('Clientes')}}</a>
            </div>
        </div>
    </form>
</div>
