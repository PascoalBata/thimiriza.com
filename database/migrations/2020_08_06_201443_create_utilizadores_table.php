<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilizadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilizadores', function (Blueprint $table) {
            $table->string('utilizador_id')->primary();
            $table->string('utilizador_nome');
            $table->string('utilizador_apelido')->nullable();
            $table->string('utilizador_telefone')->unique();
            $table->string('utilizador_email')->unique();
            $table->string('utilizador_endereco');
            $table->string('utilizador_nascimento')->nullable();
            $table->string('utilizador_genero')->default('HOMEM');
            $table->string('utilizador_previlegio');
            $table->string('utilizador_senha');
            $table->string('id_empresa');
            $table->foreign('id_empresa')->references('empresa_id')->on('empresas');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilizadores');
    }
}
