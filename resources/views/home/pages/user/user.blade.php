@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Utilizadores') }}</strong></h1>
            </div>
        </div>
        <div class="row" style="padding-bottom: 5%">
            <form method="POST" action="{{ route('store_user') }}">
                @csrf
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
                        <label for="surname" class="black-text">{{ __('Apelido') }}</label>
                        <input id="surname" type="text" class="black-text" name="surname" value="{{ old('surname') }}"
                            required>
                        @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
                        <input id="birthdate" type="text" class="black-text" name="birthdate" value="{{ old('birthdate') }}"
                            required onfocus="(this.type='date')" onblur="(this.type='text')">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="email" class="black-text">{{ __('Email') }}</label>
                        <input id="email" type="email" class="black-text" name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                        <input id="phone" type="number" data-target="20" class="black-text" name="phone"
                            value="{{ old('phone') }}" required>
                        @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <label for="address" class="black-text">{{ __('Endereço/Morada') }}</label>
                        <input id="address" type="text" class="black-text" name="address" value="{{ old('address') }}"
                            required>
                        @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <label for="password" class="black-text">{{ __('Senha') }}</label>
                        <input id="password" type="password" class="black-text" name="password"
                            value="{{ old('password') }}" required>
                        @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <label for="confirm_password" class="black-text">{{ __('Confirme a senha') }}</label>
                        <input id="confirm_password" type="password" class="black-text" name="confirm_password"
                            value="{{ old('confirm_password') }}" required>
                        @error('email')
                        <span class="red-text" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
                        <a class="waves-effect waves-light btn-small modal-trigger" href="#modal_users">Utilizadores</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Users Modal -->
    <div id="modal_users" class="modal bottom-sheet">
        <div class="modal-content">
            <h4>Utilizadores</h4>
            <div class="row" id="user_table" style="display: block;">
                <div class="col s12 m12 l12" style="overflow-x: scroll;">
                    <table class="highlight">
                        <thead>
                            <tr>
                                <th>{{ __('Nome') }}</th>
                                <th style="text-align: center;">{{ __('Email') }}</th>
                                <th style="text-align: center;">{{ __('Telefone') }}</th>
                                <th style="text-align: center;">{{ __('Genêro') }}</th>
                                <th style="text-align: center;">{{ __('Nascimento') }}</th>
                                <th style="text-align: center;">{{ __('Previlégio') }}</th>
                                <th style="text-align: center;">{{ __('Endereço') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        @if ($user->surname == 'N/A')
                                            {{ $user->name }}
                                        @endif
                                        @if ($user->surname != 'N/A')
                                            {{ $user->name }} {{ $user->surname }}
                                        @endif
                                    </td>
                                    <td style="text-align: center;">{{ $user->email }}</td>
                                    <td style="text-align: center;">{{ $user->phone }}</td>
                                    <td style="text-align: center;">{{ $user->gender }}</td>
                                    <td style="text-align: center;">{{ $user->birthdate }}</td>
                                    <td style="text-align: center;">{{ $user->privilege }}</td>
                                    <td style="text-align: center;">{{ $user->address }}</td>
                                    <td style="text-align: right;">
                                        <a class="modal-trigger waves-effect waves-light btn-small" href="#edit_user_modal"
                                            onclick="editUser(this, {{ $user->id }}, '{{ $user->name }}', '{{ $user->surname }}')">editar</a>
                                        <a class="modal-trigger waves-effect waves-light btn-small red darken-3"
                                            href="#remove_user_modal"
                                            onclick="removeUser(this, {{ $user->id }})">remover</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
        </div>
    </div>

    <div id="edit_user_modal" tabindex="-1" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>{{ __('Actualizar Utilizador') }}</h4>
            <form method="POST" id="editUserNameForm" name="editUserNameForm" action="{{ route('edit_user_name') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m4 l4">
                        <label for="name" class="black-text">{{ __('Nome') }}</label>
                        <input required id="name" type="text" class="black-text" name="name" value="{{ old('name') }}"
                            autofocus>
                    </div>
                    <div class="input-field col s12 m3 l3">
                        <label for="surname" class="black-text">{{ __('Apelido') }}</label>
                        <input required id="surname" type="text" class="black-text" name="surname"
                            value="{{ old('surname') }}" autofocus>
                    </div>
                    <div class="input-field col s12 m3 l3">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserEmailForm" name="editUserEmailForm" action="{{ route('edit_user_email') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="email" class="black-text">{{ __('Email') }}</label>
                        <input required id="email" type="email" class="black-text" name="email" value="{{ old('email') }}"
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
            <form method="POST" id="editUserPhoneForm" name="editUserPhoneForm" action="{{ route('edit_user_phone') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="phone" class="black-text">{{ __('Telefone') }}</label>
                        <input required id="phone" type="number" class="black-text" name="phone" value="{{ old('phone') }}"
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
                action="{{ route('edit_user_birthdate') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="birthdate" class="black-text">{{ __('Data de nascimento') }}</label>
                        <input id="birthdate" type="text" class="black-text" name="birthdate" value="{{ old('birthdate') }}"
                            required onfocus="(this.type='date')" onblur="(this.type='text')">
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
                action="{{ route('edit_user_address') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <label for="address" class="black-text">{{ __('Endereço') }}</label>
                        <input required id="address" type="text" class="black-text" name="address"
                            value="{{ old('address') }}" autofocus>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <button type="submit" class="waves-effect waves-light btn-small ">
                            {{ __('Salvar') }}
                            <i class="material-icons left"></i>
                        </button>
                    </div>
                </div>
            </form>
            <form method="POST" id="editUserGenderForm" name="editUserGenderForm" action="{{ route('edit_user_gender') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <select id="gender" name="gender">
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
                action="{{ route('edit_user_privilege') }}">
                @method('PUT')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m7 l7">
                        <select id="privilege" name="privilege">
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
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fechar</a>
        </div>
    </div>
    <div id="remove_user_modal" tabindex="-1" class="modal modal-fixed-footer">
        <form method="POST" id="removeUserForm" name="removeUserForm" action="{{ route('remove_user') }}">
            <div class="modal-content">
                <h4>{{ __('Remover Utilizador') }}</h4>
                <p>{{ __('Tem certeza que deseja remover este utilizador?') }}</p>
                @method('DELETE')
                @csrf
                <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
                <div class="row">
                    <div class="input-field col s12 m12 l12">
                        <input id="user" type="text" class="black-text" name="user" value="{{ old('user') }}" autofocus
                            disabled>
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
@endsection
@section('script')
    <script>
        function editUser(button, id, name, surname) {
            var tr = button.parentElement.parentElement;
            editUserNameForm.id.value = id;
            editUserEmailForm.id.value = id;
            editUserPhoneForm.id.value = id;
            editUserAddressForm.id.value = id;
            editUserGenderForm.id.value = id;
            editUserPrivilegeForm.id.value = id;
            editUserBirthdateForm.id.value = id;
            editUserNameForm.name.value = name;
            editUserNameForm.surname.value = surname;
            editUserEmailForm.email.value = tr.cells[1].innerHTML;
            editUserPhoneForm.phone.value = tr.cells[2].innerHTML;
            editUserAddressForm.address.value = tr.cells[6].innerHTML;
            //editUserBirthdateForm.birthdate.value = tr.cells[4].innerHTML;
            //editUserGenderForm.gender.value = tr.cells[0].innerHTML;
            //editUserPrivilegeForm.privilege.value = tr.cells[0].innerHTML;
        }

        function removeUser(button, id) {
            var tr = button.parentElement.parentElement;
            removeServiceForm.id.value = id;
            removeServiceForm.service.value = tr.cells[0].innerHTML + " <<->> " + tr.cells[1].innerHTML;
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if (session('user_notification'))
        <div class="alert alert-success">
            <script>
                M.toast({
                    html: '{{ session('
                    user_notification ') }}',
                    classes: 'rounded',
                    displayLength: 1000
                });

            </script>
        </div>
    @endif
@endsection
