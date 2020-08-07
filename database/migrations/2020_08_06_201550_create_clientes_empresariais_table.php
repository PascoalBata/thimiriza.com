<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesEmpresariaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_empresariais', function (Blueprint $table) {
            $table->string('cliente_e_id')->primary();
            $table->string('cliente_e_nome');
            $table->string('cliente_e_telefone');
            $table->string('cliente_e_email');
            $table->string('cliente_e_endereco');
            $table->string('cliente_e_nuit')->unique();
            $table->string('id_utilizador');
            $table->foreign('id_utilizador')->references('utilizador_id')->on('utilizadores');
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
        Schema::dropIfExists('clientes_empresariais');
    }
}
