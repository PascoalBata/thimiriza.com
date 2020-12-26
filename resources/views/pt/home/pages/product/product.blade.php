@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Produtos') }}</strong></h1>
            </div>
        </div>
        @include('pt.home.pages.product.create')
    </div>
    <!-- Products Modals -->
    @include('pt.home.pages.product.index')
    @includeWhen($is_edit, 'pt.home.pages.product.edit')
@endsection

<!-- Scripts -->
@section('script')
    <script>
        function displayProductsTable() {
            if (document.getElementById('products_table').style.display === 'none') {
                document.getElementById('products_table').style.display = 'block';
            } else {
                document.getElementById('products_table').style.display = 'none';
            }
        }

        $(document).ready(function() {
            $('.modal').modal();
        });

    </script>
    @if ($is_edit)
        <script>
            $(document).ready(function() {
                $('#table_products_modal').modal('open');
                $('#edit_product_modal').modal('open');
            });
        </script>
    @endif

    @if ($is_destroy)
        <script>
            $(document).ready(function() {
                $('#table_products_modal').modal('open');
                $('#destroy_product_modal').modal('open');
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
