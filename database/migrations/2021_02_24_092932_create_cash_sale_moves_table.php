<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashSaleMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_sale_moves', function (Blueprint $table) {
            $table->id();
            $table->string('sale_type');
            $table->string('id_product_service');
            $table->string('product_service');
            $table->string('description');
            $table->double('price');
            $table->integer('quantity');
            $table->double('discount');
            $table->double('iva');
            $table->unsignedBigInteger('id_cash_sale');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_cash_sale')->references('id')->on('cash_sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_sale_moves');
    }
}
