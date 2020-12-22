@extends('home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(0%);">
    <div id='product_content'>
        @include('home.pages.product.product')
    </div>
    <div id='service_content'>
        @include('home.pages.service.service')
    </div>
</div>
@endsection
@section('script')
    @if (session('status'))
    <div class="alert alert-success">
        <script>
            M.toast({html: '{{ session('status') }}', classes: 'rounded', displayLength: 1000});
        </script>
    </div>
    @endif
@endsection
