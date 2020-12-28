<div id="remove_client_enterprise_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="removeClientEnterpriseForm" name="removeClientEnterpriseForm" action="{{ route('remove_client_enterprise') }}">
        <div class="modal-content">
            <h4>{{ __('Remover Cliente Empresarial')}}</h4>
            <p>{{__('Tem certeza que deseja remover este cliente empresarial?')}}</p>
            <p>{{__('Atenção: Ao remover este cliente, todas as facturas pertencentes ao mesmo serão eliminadas!')}}</p>
            @method('DELETE')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <input id="client_enterprise" type="text" class="black-text" name="client_enterprise" value="{{ old('client_enterprise') }}" autofocus disabled>
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
