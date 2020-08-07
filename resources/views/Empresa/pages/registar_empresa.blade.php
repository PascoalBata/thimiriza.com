@extends('layouts.app')
@section ('title', 'Nova conta Thimiriza')
@section('content')
<div class="container grey lighten-5" style="opacity: 80%; transform: translateY(2%);">
    <div class="row">
        <div class="col s12 m12 l12 center-align">
            <h1 class="display-4 black-text"><strong>Registo da Empresa</strong></h1>
        </div>
    </div>
    <div class="container row">
        <div class="col s12 m12 l12">
            @include('Empresa.includes.alerts.alert')
            <form name="registarEmpresaForm" method="POST" onsubmit="return submeter()"
                  action="{{route('gravar_criar_empresa')}}">
                  @csrf
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input type="text" class="black-text" id="empresa_nome" name="empresa_nome"
                               required>
                        <label for="empresa_nome" class="black-text">Nome da empresa</label>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <select id="empresa_tipo" name="empresa_tipo">
                            <option value="" disabled selected>Tipo de Empresa</option>
                            <option value="NORMAL">Normal</option>
                            <option value="ISPC">ISPC</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input type="email" class="black-text" id="empresa_email" name="empresa_email"
                               required>
                        <label for="empresa_email" class="black-text">Email</label>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <input type="tel" class="black-text" id="empresa_telefone" name="empresa_telefone"
                               required>
                        <label for="empresa_telefone" class="black-text">Telefone</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input type="text" class="black-text" id="empresa_nuit" name="empresa_nuit"
                               required>
                        <label for="empresa_nuit" class="black-text">NUIT</label>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <input type="text" class="black-text" id="empresa_endereco" name="empresa_endereco"
                               required>
                        <label for="empresa_endereco" class="black-text">Endereço</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input type="password" class="black-text" id="empresa_senha" name="empresa_senha"
                               required>
                        <label for="empresa_senha" class="black-text">Senha</label>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <input type="password" class="black-text" id="senha_confirmacao"
                               name="senha_confirmacao" required>
                        <label for="senha_confirmacao" class="black-text">Confirme a senha</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <a href="{{route('raiz')}}">Já possuo uma conta (Login)</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <button class="waves-effect waves-light btn" type="submit">Registar</button>
                        <button class="waves-effect waves-light btn" type="reset">Limpar</button>
                    </div>
                </div>
            </form>
        </div>
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
        if(document.getElementById('senha_confirmacao').value !== document.getElementById('empresa_senha').value){
            window.alert('As senhas sao diferentes');
            return false;
        }
        return true;
    }
</script>
@endsection