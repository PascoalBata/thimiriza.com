<div id="table_clients_enterprise_modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>{{__('Clientes Empresariais')}}</h4>
        <div class="row" id="client_enterprise_table" style="display: block;">
            <div class="col s12 m12 l12" style="overflow-x: scroll;">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Nome') }}</th>
                            <th style="text-align: center;">{{ __('Email') }}</th>
                            <th style="text-align: center;">{{ __('Telefone') }}</th>
                            <th style="text-align: center;">{{ __('NUIT') }}</th>
                            <th style="text-align: center;">{{ __('Endereço') }}</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients_enterprise as $client_enterprise)
                        <tr>
                            <td>{{$client_enterprise->id}}</td>
                            <td>{{$client_enterprise->name}}</td>
                            <td style="text-align: center;">{{$client_enterprise->email}}</td>
                            <td style="text-align: center;">{{$client_enterprise->phone}}</td>
                            <td style="text-align: center;">{{$client_enterprise->nuit}}</td>
                            <td style="text-align: center;">{{$client_enterprise->address}}</td>
                            <td style="text-align: right;">
                                <a class="modal-trigger waves-effect waves-light btn-small" href="{{route('edit_client_enterprise', $client_enterprise->id)}}" style="width: 100%;">editar</a>
                                <form method="POST" action="{{ route('destroy_client_enterprise', $client_enterprise->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button style="width: 100%;" type="submit"
                                        class="waves-effect waves-light btn-small red darken-3">remover</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $clients_enterprise->links() !!}
            </div>
        </div>
    </div>
</div>
