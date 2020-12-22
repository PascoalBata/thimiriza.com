<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsEnterpriseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_enterprise', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('nuit');
            $table->unsignedBigInteger('id_company');
            $table->BigInteger('created_by'); //id_user
            $table->BigInteger('updated_by')->nullable();; //id_user
            $table->timestamps();

            $table->foreign('id_company')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_enterprise');
    }
}
