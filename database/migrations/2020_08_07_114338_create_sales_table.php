<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('id_product_service');
            $table->string('type_client');
            $table->string('id_client');
            $table->double('quantity');
            $table->double('discount');
            $table->double('iva');
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
        Schema::dropIfExists('sales');
    }
}
