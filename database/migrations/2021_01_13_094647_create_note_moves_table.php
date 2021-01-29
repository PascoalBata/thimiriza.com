<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_moves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_invoice_note');
            $table->string('type_product_service');
            $table->bigInteger('id_product_service');
            $table->string('product_service');
            $table->string('product_service_description');
            $table->text('description');
            $table->float('value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_invoice_note')->references('id')->on('invoice_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_moves');
    }
}
