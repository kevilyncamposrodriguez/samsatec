<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('teams');
            $table->string('id_card', 12);
            $table->unsignedBigInteger('type_id_card');
            $table->foreign('type_id_card')->references('id')->on('type_id_cards');
            $table->string('name', 80);
            $table->unsignedBigInteger('id_province');
            $table->foreign('id_province')->references('id')->on('provinces');
            $table->unsignedBigInteger('id_canton');
            $table->foreign('id_canton')->references('id')->on('cantons');
            $table->unsignedBigInteger('id_district');
            $table->foreign('id_district')->references('id')->on('districts');
            $table->string('other_signs');
            $table->unsignedBigInteger('id_country_code');
            $table->foreign('id_country_code')->references('id')->on('country_codes');
            $table->string('phone', 8);
            $table->string('emails');
            $table->integer('commission');
            $table->enum('active', [0, 1])->default("1");
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
        Schema::dropIfExists('sellers');
    }
}
