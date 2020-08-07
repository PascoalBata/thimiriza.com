<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesSingularesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_singulares', function (Blueprint $table) {
            $table->string('cliente_s_id')->primary();
            $table->string('cliente_s_nome');
            $table->string('cliente_s_apelido');
            $table->string('cliente_s_telefone')->nullable();
            $table->string('cliente_s_email')->nullable();
            $table->string('cliente_s_endereco')->nullable();
            $table->string('cliente_s_nuit')->unique();
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
        Schema::dropIfExists('clientes_singulares');
    }
}
