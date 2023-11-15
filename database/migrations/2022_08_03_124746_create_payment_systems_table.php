<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company')->nullable();
            $table->foreign('id_company')->references('id')->on('teams');            
            $table->integer('id_pay');
            $table->string('pay_key');
            $table->double('pay_amount',18,2);
            $table->string('pay_detail');
            $table->string('user');            
            $table->integer('fe');
            $table->integer('months');
            $table->unsignedBigInteger('id_invoice')->nullable();
            $table->foreign('id_invoice')->references('id')->on('documents');
            $table->foreignId('plan_id')->nullable();
            $table->timestamp('start_pay')->nullable();
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
        Schema::dropIfExists('payment_systems');
    }
};
