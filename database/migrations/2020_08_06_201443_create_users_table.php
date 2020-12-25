<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('phone');
            $table->string('address');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('privilege')->default('PARCIAL');
            $table->unsignedBigInteger('id_company');
            $table->string('status')->default('ON');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->BigInteger('created_by'); //id_user
            $table->BigInteger('updated_by')->nullable(); //id_user
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
        Schema::dropIfExists('users');
    }
}
