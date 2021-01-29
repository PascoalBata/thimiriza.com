<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_notes', function (Blueprint $table) {
            $table->id();
            $table->string('type_product_service');
            $table->string('id_product_service');
            $table->text('description');
            $table->float('value');
            $table->string('type');
            $table->BigInteger('id_invoice');
            $table->unsignedBigInteger('id_company');
            $table->BigInteger('created_by'); //id_user
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
        Schema::dropIfExists('temp_notes');
    }
}
