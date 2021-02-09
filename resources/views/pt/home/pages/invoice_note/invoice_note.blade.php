@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Notas') }}</strong></h1>
            </div>
        </div>
        @include('pt.home.pages.invoice_note.create')
    </div>
    @if ($hasTemp_notes || ($actual_serie_number !== '' && $actual_serie_number !== null))
        @include('pt.home.pages.invoice_note.items')
    @endif
    <!-- Invoice Notes Modals -->
    @includeWhen($is_index, 'pt.home.pages.invoice_note.index')
    @includeWhen($is_edit, 'pt.home.pages.invoice_note.edit')
@endsection

<!-- Scripts -->
@section('script')
@if ($is_index)
    <script>
        $(document).ready(function() {
            $('#notes_table_modal').modal('open');
        });
    </script>
    @endif

    @if ($is_edit)
        <script>
            $(document).ready(function() {
                $('#notes_table_modal').modal('open');
                $('#edit_note_modal').modal('open');
            });
        </script>
    @endif

    @if ($is_destroy)
        <script>
            $(document).ready(function() {
                $('#table_notes_modal').modal('open');
                $('#destroy_note_modal').modal('open');
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('.modal').modal();
            $('input#input_text, textarea#description').characterCounter();
            if($('select[name=product_service_type] option').filter(':selected').val() === 'PRODUCT'){
                $('#select_type_label').text('Produto');
                $('#product_service').attr('list', 'list_products');
                $('#product_service').val();
            }
            if($('select[name=product_service_type] option').filter(':selected').val() === 'SERVICE'){
                $('#select_type_label').text('Serviço');
                $('#product_service').attr('list', 'list_services');
                $('#product_service').val();
            }
        });

        function onSelect() {
            var input = document.getElementById("invoice_number");
            var val = document.getElementById("invoice_number").value;
            if(val.trim() === ''){
                window.alert('Escolha a Factura');
            }else{
                var id = encodeURIComponent(encodeURIComponent(val));
                var url = "{{ route('select_invoice', '' ) }}" + "/" + id;
                location.href = url;
            }

        }

        function selectType(click) {
            if (click.value == 'SERVICE') {
                document.getElementById('select_type_label').innerText = 'Serviço';
                document.getElementById('product_service').setAttribute('list', 'list_services')
                document.getElementById('product_service').value = null;
            }
            if (click.value == 'PRODUCT') {
                document.getElementById('select_type_label').innerText = 'Produto';
                document.getElementById('product_service').setAttribute('list', 'list_products')
                document.getElementById('product_service').value = null;
            }
        }

        $('form').on('reset', function(e)
        {
            setTimeout(function() {
                $('input#invoice_number').focus();
            }, 100);
        });
    </script>

    @if (session('operation_status'))
        <div class="alert alert-success">
            <script>
                M.toast({html: '{{ session('operation_status') }}', classes: 'rounded', displayLength: 2000});
            </script>
        </div>
    @endif
@endsection
