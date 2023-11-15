<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseOtherChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_other_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_expense');
            $table->foreign('id_expense')->references('id')->on('expenses');
            $table->string('type_document', 2);
            $table->string('idcard', 12);
            $table->string('consecutive', 12);
            $table->string('name', 100);
            $table->string('detail', 160);
            $table->double('amount', 18, 5);
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
        Schema::dropIfExists('expense_other_charges');
    }
}
