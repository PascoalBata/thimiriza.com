<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->string('empresa_id')->primary();
            $table->string('empresa_nome');
            $table->string('empresa_tipo');
            $table->string('empresa_nuit')->unique();
            $table->string('empresa_telefone');
            $table->string('empresa_endereco');
            $table->string('empresa_numero_conta')->nullable();
            $table->string('empresa_titular_conta')->nullable();
            $table->string('empresa_nib')->nullable();
            $table->string('empresa_nome_banco')->nullable();
            $table->string('empresa_email')->unique();
            $table->string('empresa_logo')->nullable();
            $table->string('empresa_estado')->default('OFF');
            $table->unsignedBigInteger('id_pacote');
            $table->foreign('id_pacote')->references('pacote_id')->on('pacotes');
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
        Schema::dropIfExists('empresas');
    }
}
