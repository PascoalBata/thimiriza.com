@extends('pt.Admin.layouts.app')
@section('username', $user)
@section ('title', 'Nova conta Thimiriza')
@section('content')
    <div class="row">
        <div class="col s12 m12 l12 center-align">
            <h1 class="display-4 black-text"><strong>Empresas Registadas</strong></h1>
        </div>
    </div>
    <div class="row">
        <table class="table-responsive">
            <thead>
            <tr>
                <th>ID</th>
                <th>NOME</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>FACTURAS</th>
                <th>UTILIZADORES</th>
                <th>ESTADO</th>
                <th>CRIACAO</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($companies as $company)
            <tr>
                <td>{{$company->id}}</td>
                <td>{{$company->name}}</td>
                <td>{{$company->phone}}</td>
                <td>{{$company->email}}</td>
                <td>{{$company->invoices}}</td>
                <td>{{$company->users}}</td>
                <td>{{$company->status}}</td>
                <td>{{$company->created_at}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {!!$companies->links()!!}
    </div>
@endsection
@section('script')
<script>

</script>
@endsection
