<div id="table_clients_singular_modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>{{__('Clientes Singulares')}}</h4>
        <div class="row" id="client_singular_table" style="display: block;">
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
                        @foreach ($clients_singular as $client_singular)
                        <tr>
                            <td>{{$client_singular->id}}</td>
                            <td>{{$client_singular->name}} {{$client_singular->surname}}</td>
                            <td style="text-align: center;">{{$client_singular->email}}</td>
                            <td style="text-align: center;">{{$client_singular->phone}}</td>
                            <td style="text-align: center;">{{$client_singular->nuit}}</td>
                            <td style="text-align: center;">{{$client_singular->address}}</td>
                            <td style="text-align: right;">
                                <a class="modal-trigger waves-effect waves-light btn-small" href="{{route('edit_client_singular', $client_singular->id)}}" style="width: 100%;">editar</a>
                                <a class="modal-trigger waves-effect waves-light btn-small red darken-3" href="#remove_client_singular_modal" onclick="removeClientSingular(this, {{$client_singular->id}})" style="width: 100%;">remover</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $clients_singular->links() !!}
            </div>
        </div>
    </div>
</div>
