@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
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
@include('pt.home.pages.service.index')
@includeWhen($is_edit, 'pt.home.pages.service.edit')
@endsection

@section('script')
    <script>
        function displayServiceTable() {
            if (document.getElementById('services_table').style.display === 'none') {
                document.getElementById('services_table').style.display = 'block';
            } else {
                document.getElementById('services_table').style.display = 'none';
            }
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if ($is_edit)
        <script>
            $(document).ready(function() {
                $('#services_table_modal').modal('open');
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

    @if (session('operation_status'))
        <div class="alert alert-success">
            <script>
                M.toast({html: '{{ session('operation_status') }}', classes: 'rounded', displayLength: 2000});
            </script>
        </div>
    @endif
@endsection
