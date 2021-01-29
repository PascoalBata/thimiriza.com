<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_notes', function (Blueprint $table) {
            $table->id();
            $table->float('value');
            $table->string('type');
            $table->unsignedBigInteger('id_invoice');
            $table->BigInteger('created_by'); //id_user
            $table->BigInteger('updated_by')->nullable(); //id_user
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_invoice')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_notes');
    }
}
