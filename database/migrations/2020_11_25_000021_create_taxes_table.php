<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_taxes_code');
            $table->foreign('id_taxes_code')->references('id')->on('taxes_codes');
            $table->unsignedBigInteger('id_rate_code')->nullable();
            $table->foreign('id_rate_code')->references('id')->on('rate_codes');
            $table->float("rate", 4, 2);                       
            $table->string("description");
            $table->unsignedBigInteger('id_exoneration')->nullable();
            $table->foreign('id_exoneration')->references('id')->on('exonerations');  
            $table->float("exoneration_amount", 18,5)->default(0);   
            $table->float("tax_net", 18,5);   
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
        Schema::dropIfExists('taxes');
    }
}
