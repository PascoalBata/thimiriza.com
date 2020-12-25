<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('client_type');
            $table->string('id_client');
            $table->double('price');
            $table->string('status');
            $table->unsignedBigInteger('id_company');
            $table->BigInteger('created_by'); //id_user
            $table->BigInteger('updated_by')->nullable();; //id_user
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('invoices');
    }
}
