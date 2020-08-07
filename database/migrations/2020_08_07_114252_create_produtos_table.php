<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->string('produto_id')->primary();
            $table->string('produto_nome');
            $table->text('produto_descricao')->nullable();
            $table->double('produto_preco');
            $table->integer('produto_quantidade');
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
        Schema::dropIfExists('produtos');
    }
}
