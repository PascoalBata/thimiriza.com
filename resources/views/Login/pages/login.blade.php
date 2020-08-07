@extends('layouts.app')
@section ('title', 'Login Thimiriza')
@section('content')
<div class="row">
    <div class="container grey lighten-5" style="opacity: 80%; position: relative; transform: translateY(20%);">
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>Login</strong></h1>
            </div>
        </div>
        <div class="row" style="margin-left: 15%; margin-right: 15%; padding-bottom: 5%">
            <div class="col s12 m12 l12">
                <form name="login_empresa" method="POST" action="./login_empresa/login_empresa.php">
                    <div class="row">
                        <div class="input-field col s12 m12 l12">
                            <input type="text" class="black-text" id="inputUtilizador" name="inputUtilizador" required>
                            <label for="inputUtilizador" class="black-text">ID ou Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m12 l12">
                            <input type="password" class="black-text" id="inputSenha" name="inputSenha" required>
                            <label for="inputSenha" class="black-text">Senha</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <a href="{{route('criar_empresa')}}">Não possuo conta (Registrar)</a><br/>
                            <a href="recuperar_conta">Esqueci a senha</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            <button type="submit" class="waves-effect waves-light btn">Entrar</button>
                            <button type="reset" class="waves-effect waves-light btn">Limpar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection