<div id="table_users_modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>Utilizadores</h4>
        <div class="row" id="user_table" style="display: block;">
            <div class="col s12 m12 l12" style="overflow-x: scroll;">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
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
                                <td style="text-align: center;">{{ $user->id }}</td>
                                <td>
                                    @if ($user->privilege === 'ADMIN')
                                        {{ $user->name }}
                                    @endif
                                    @if ($user->privilege !== 'ADMIN')
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
                                    <a style="width: 100%;" class="waves-effect waves-light btn-small"
                                        @if ($user->privilege === 'ADMIN')
                                            disabled
                                        @endif
                                        href="{{ route('edit_user', $user->id) }}">editar</a>
                                    <form method="POST" action="{{ route('destroy_user', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button style="width: 100%;" type="submit"
                                            @if ($user->privilege === 'ADMIN')
                                                disabled
                                            @endif
                                            onclick="return confirm('Tem certeza que deseja remover este utilizador?')"
                                            class="waves-effect waves-light btn-small red darken-3">remover</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $users->links() !!}
            </div>
        </div>
    </div>
</div>
