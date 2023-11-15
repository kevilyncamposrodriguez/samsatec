<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_credit');
            $table->foreign('id_credit')->references('id')->on('credits');
            $table->unsignedBigInteger('id_count');
            $table->foreign('id_count')->references('id')->on('counts');
            $table->integer('month_payment');
            $table->integer('year_payment')->nullable();
            $table->timestamp('date_pay');
            $table->double('capital_contribution', 18, 5);
            $table->double('loan_interest', 18, 5);
            $table->double('other_interest', 18, 5);
            $table->double('interest_late_payment', 18, 5);
            $table->double('safe', 18, 5);
            $table->double('endorsement', 18, 5);
            $table->double('policy', 18, 5);
            $table->double('saving', 18, 5);
            $table->double('other_saving', 18, 5);
            $table->double('total_fee', 18, 5);
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
        Schema::dropIfExists('credit_payments');
    }
}
