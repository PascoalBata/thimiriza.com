<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('nuit')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_owner')->nullable();
            $table->string('bank_account_nib')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('email')->unique();
            $table->string('logo')->nullable();
            $table->string('status')->default('ON');
            $table->unsignedBigInteger('id_package');
            $table->timestamp('email_verified_at')->useCurrent();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_package')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
