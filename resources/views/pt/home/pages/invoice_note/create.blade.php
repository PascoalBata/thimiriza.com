<div class="row" style="padding-bottom: 5%">
    @if (!($actual_serie_number !== '' && $actual_serie_number !== null))
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="invoice_number" class="black-text">{{ __('Nº. da factura') }}</label>
                <input id="invoice_number" list="invoices" type="text" autocomplete="off" class="black-text"
                    name="invoice_number" onclick="onSelect();" @if ($hasTemp_notes) value="{{ $actual_serie_number }}"
                disabled
        @else
                value="{{ old('invoice_number') }}"
                placeholder="Ex.:2020/4"
        @endif

        @if ($actual_serie_number !== '' && $actual_serie_number !== null)
                hidden
                disabled
        @endif
        required>
        <datalist id="invoices">
        @foreach ($invoices_series as $invoice_serie)
            <option value='{{ $invoice_serie }}'>
        @endforeach
        </datalist>
            </div>
        </div>
    @endif

@if ($hasTemp_notes || ($actual_serie_number !== '' && $actual_serie_number !== null))
    <form method="POST" name="saleForm" action="{{ route('add_invoice_note_item') }}">
        @method('POST')
        @csrf
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <label for="invoice_number" class="black-text active">{{ __('Nº. da factura') }}</label>
                <input id="invoice_number" type="text" autocomplete="off" class="black-text" name="invoice_number"
                    value="{{ $actual_serie_number }}" required
                    @if ($actual_serie_number !== null)
                        readonly
                    @endif/>
            </div>
            <div class="input-field col s12 m6 l6">
                <select id="type" name="type">
                    <optgroup label="{{ __('Tipo de nota') }}">
                        @if ($hasTemp_notes)
                            @if($temp_notes[0]->type === 'CREDIT')
                            <option value="CREDIT" selected>{{ __('Crédito') }}</option>
                            @endif
                            @if($temp_notes[0]->type === 'DEBIT')
                            <option value="DEBIT" selected>{{ __('Dédito') }}</option>
                            @endif
                        @else
                        <option value="CREDIT">{{ __('Crédito') }}</option>
                        <option value="DEBIT">{{ __('Dédito') }}</option>
                        @endif
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <select id="product_service_type" name="product_service_type" onchange="selectType(this)">
                    <optgroup label="{{ __('Tipo') }}">
                        @if ($products->count() > 0)
                            <option value="PRODUCT">{{ __('Produto') }}</option>
                        @endif
                        @if ($services->count() > 0)
                            <option value="SERVICE">{{ __('Serviço') }}</option>
                        @endif
                    </optgroup>
                </select>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="product_service" id="select_type_label" name="select_type_label"
                    class="black-text">{{ __('Produto') }}</label>
                <input id="product_service" list="list_products" type="text" autocomplete="off" class="black-text"
                    name="product_service" value="{{ old('product_service') }}" required>
                <datalist id="list_products">
                    @foreach ($products as $product)
                        <option value='{{ $product->id_product_service }}'>{{ $product->product_service }}
                            {{ __('===') }} {{ $product->description }}</option>
                    @endforeach
                </datalist>
                <datalist id="list_services">
                    @foreach ($services as $service)
                        <option value='{{ $service->id_product_service }}'>{{ $service->product_service }}
                            {{ __('===') }} {{ $service->description }}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <textarea id="description" name="description" class="materialize-textarea" data-length="200"></textarea>
                <label for="description" class="black-text">Descrição</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <label for="value" class="black-text">{{ __('Valor') }}</label>
                <input id="value" type="number" class="black-text" name="value" value="{{ old('value') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <button type="submit" class="waves-effect waves-light btn-small">
                    {{ __('Adicionar') }}
                    <i class="material-icons right">archive</i>
                </button>
                <button type="reset" class="waves-effect waves-light btn-small">
                    {{ __('Limpar') }}
                    <i class="material-icons right"></i>
                </button>
                <a class="waves-effect waves-light btn-small"
                    href="{{ route('index_invoice_note') }}"> {{ __('Notas') }}
                </a>
            </div>
        </div>
    </form>
@endif
</div>
