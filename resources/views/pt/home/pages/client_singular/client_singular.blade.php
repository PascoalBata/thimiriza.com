@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Cliente Singular') }}</strong></h1>
            </div>
        </div>
        @include('pt.home.pages.client_singular.create')
    </div>
    <!-- Users Modals -->
    @include('pt.home.pages.client_singular.index')
    @includeWhen($is_edit, 'pt.home.pages.client_singular.edit')
@endsection

@section('script')
    <script>
        function displayClientEnterpriseTable() {
            if (document.getElementById('clients_singular_table').style.display === 'none') {
                document.getElementById('clients_singular_table').style.display = 'block';
            } else {
                document.getElementById('clients_singular_table').style.display = 'none';
            }
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

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
                $('#table_clients_singular_modal').modal('open');
                $('#edit_client_singular_modal').modal('open');
            });
        </script>
    @endif

    @if ($is_destroy)
        <script>
            $(document).ready(function() {
                $('#table_clients_singular_modal').modal('open');
                $('#destroy_clients_singular_modal').modal('open');
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
