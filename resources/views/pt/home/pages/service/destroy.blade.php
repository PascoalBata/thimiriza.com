<div id="remove_service_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="removeProductForm" name="removeServiceForm" action="{{ route('remove_service') }}">
        <div class="modal-content">
            <h4>{{ __('Remover Serviço')}}</h4>
            <p>{{__('Tem certeza que deseja remover este serviço?')}}</p>
            @method('DELETE')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <input id="service" type="text" class="black-text" name="service" value="{{ old('service') }}" autofocus disabled>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="waves-effect waves-light btn-small " >
                {{ __('SIM') }}
                <i class="material-icons left"></i>
            </button>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">{{ __('NÃO') }}</a>
        </div>
    </form>
</div>
