<div id="services_table_modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>{{__('Serviços')}}</h4>
        <div class="row" id="services_table" style="display: block;">
            <div class="col s12 m12 l12">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Nome') }}</th>
                            <th style="text-align: center;">{{ __('Descrição') }}</th>
                            <th style="text-align: center;">{{ __('IVA') }}</th>
                            <th style="text-align: center;">{{ __('Preço') }}</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{$service->name}}</td>
                            <td style="text-align: center;">{{$service->description}}</td>
                            <td style="text-align: center;">
                                <label class="black-text">
                                    <input disabled type="checkbox" class="filled-in"
                                        @if ($service->iva === 'on')
                                            checked
                                        @endif
                                    />
                                    <span></span>
                                </label>
                            </td>
                            <td style="text-align: center;">{{number_format($service->price, 2, ',', '.')}} {{ __('MT') }}</td>
                            <td style="text-align: right;">
                                <a style="width: 100%;" class="waves-effect waves-light btn-small"
                                    href="{{ route('edit_service', $service->id) }}">editar</a>
                                <form method="POST" action="{{ route('destroy_service', $service->id) }}">
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
                {!! $services->links() !!}
            </div>
        </div>
    </div>
</div>
