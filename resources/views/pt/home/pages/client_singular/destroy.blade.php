<div id="remove_client_singular_modal" tabindex="-1" class="modal modal-fixed-footer">
    <form method="POST" id="removeClientSingularForm" name="removeClientSingularForm" action="{{ route('remove_client_singular') }}">
        <div class="modal-content">
            <h4>{{ __('Remover Cliente Singular')}}</h4>
            <p>{{__('Tem certeza que deseja remover este cliente singular?')}}</p>
            <p>{{__('Atenção: Ao remover este cliente, todas as facturas pertencentes ao mesmo serão eliminadas!')}}</p>
            @method('DELETE')
            @csrf
            <input id="id" type="number" name="id" value="{{ old('id') }}" hidden>
            <div class="row">
                <div class="input-field col s12 m12 l12">
                    <input id="client_singular" type="text" class="black-text" name="client_singular" value="{{ old('client_singular') }}" autofocus disabled>
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
