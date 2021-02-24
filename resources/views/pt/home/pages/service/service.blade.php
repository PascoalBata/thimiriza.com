@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('style')
    <style>
        #paginator{

        }
    </style>
@endsection
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Serviços') }}</strong></h1>
        </div>
    </div>
    @include('pt.home.pages.service.create')
</div>

<!-- Users Modal -->
@includeWhen($is_index, 'pt.home.pages.service.index')
@includeWhen($is_edit, 'pt.home.pages.service.edit')
@endsection

@section('script')
    @if ($is_index)
    <script>
        $(document).ready(function() {
            $('#services_table_modal').modal('open');
        });
    </script>
@endif
    @if ($is_edit)
        <script>
            $(document).ready(function() {
                $('#edit_service_modal').modal('open');
            });
        </script>
    @endif

    @if ($is_destroy)
        <script>
            $(document).ready(function() {
                $('#services_table_modal').modal('open');
                $('#destroy_service_modal').modal('open');
            });
        </script>
    @endif
@endsection
