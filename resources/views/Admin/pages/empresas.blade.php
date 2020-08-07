@extends('layouts.app')
@section ('title', 'Nova conta Thimiriza')
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; transform: translateY(2%);">
    <div class="row">
        <div class="col s12 m12 l12 center-align">
            <h1 class="display-4 black-text"><strong>Registo da Empresa</strong></h1>
        </div>
    </div>
    <div class="row">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>NOME</th>
                <th>UTILIZADORES</th>
                <th>ESTADO</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($empresas as $empresa)
            <tr>
                <td>{{$empresa->empresa_id}}</td>
                <td>{{$empresa->empresa_nome}}</td>
                <td>"..."</td>
                <td>{{$empresa->empresa_estado}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $('select').formSelect();
    });

    function submeter() {
        if (!(document.getElementById('empresa_tipo').value === "ISPC" ||
            document.getElementById('empresa_tipo').value === "NORMAL")) {
            window.alert('Escolha o tipo de empresa.')
            return false;
        }
        if (document.getElementById('senha_confirmacao').value !== document.getElementById('empresa_senha').value) {
            window.alert('As senhas sao diferentes');
            return false;
        }
        return true;
    }
</script>
@endsection