@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div class="row center-align">
        <div class="col s12 m12 l12">
            <h1 class="display-4 black-text"><strong>{{ __('Cliente Empresarial') }}</strong></h1>
        </div>
    </div>
    @include('pt.home.pages.client_enterprise.create')
</div>
<!-- Users Modals -->
@include('pt.home.pages.client_enterprise.index')
@includeWhen($is_edit, 'pt.home.pages.client_enterprise.edit')
@endsection

@section('script')
<script>
        function changeClient(value) {
            if (value === 'ENTERPRISE') {
                window.history.replaceState(null, 'Thimiriza', '/clients_enterprise');
                url = window.location.href;
                window.location = url;
            }
            if (value === 'SINGULAR') {
                url = window.history.replaceState(null, 'Thimiriza', '/clients_singular');
                url = window.location.href;
                window.location = url;
            }
        }

    </script>
    @if ($is_edit)
        <script>
            $(document).ready(function() {
                $('#table_clients_enterprise_modal').modal('open');
                $('#edit_client_enterprise_modal').modal('open');
            });
        </script>
    @endif

    @if ($is_destroy)
        <script>
            $(document).ready(function() {
                $('#table_clients_enterprise_modal').modal('open');
                $('#destroy_clients_enterprise_modal').modal('open');
            });
        </script>
    @endif
@endsection
