<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 15);
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('teams');
            $table->string('id_card', 12);
            $table->unsignedBigInteger('type_id_card');
            $table->foreign('type_id_card')->references('id')->on('type_id_cards');
            $table->unsignedBigInteger('id_price_list')->nullable();
            $table->foreign('id_price_list')->references('id')->on('price_lists');
            $table->string('name_client', 80)->unique();
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
            $table->unsignedBigInteger('id_sale_condition');
            $table->foreign('id_sale_condition')->references('id')->on('sale_conditions');
            $table->integer('time');
            $table->float("total_credit", 18, 5)->default(0);
            $table->unsignedBigInteger('id_currency');
            $table->foreign('id_currency')->references('id')->on('currencies');
            $table->unsignedBigInteger('id_payment_method');
            $table->foreign('id_payment_method')->references('id')->on('payment_methods');
            $table->enum('active', [0, 1])->default("1");
            $table->string('notes',5000);
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
        Schema::dropIfExists('clients');
    }
}
