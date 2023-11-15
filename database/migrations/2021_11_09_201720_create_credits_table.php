<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->string('financial_entity');
            $table->string('credit_number');
            $table->timestamp('date_issue');
            $table->integer('pay_day');
            $table->double('credit_mount', 18, 5);
            $table->double('formalization_expenses', 18, 5);
            $table->integer('credit_rate');
            $table->integer('period');
            $table->string('currency', 3);
            $table->enum('taxed', [0, 1])->default("0");
            $table->json('other_expenses')->nullable();
            $table->json('savings')->nullable();
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
        Schema::dropIfExists('credits');
    }
}
