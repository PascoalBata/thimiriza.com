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
    <!-- Invoice Notes Modals -->
    @include('pt.home.pages.invoice_note.index')
    @includeWhen($is_edit, 'pt.home.pages.invoice_note.edit')
@endsection

<!-- Scripts -->
@section('script')
    <script>
        $(document).ready(function() {
            $('.modal').modal();
            $('input#input_text, textarea#description').characterCounter();
        });
    </script>
    @if ($is_edit)
        <script>
            $(document).ready(function() {
                $('#table_notes_modal').modal('open');
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

    @if (session('operation_status'))
        <div class="alert alert-success">
            <script>
                M.toast({html: '{{ session('operation_status') }}', classes: 'rounded', displayLength: 2000});
            </script>
        </div>
    @endif
@endsection
