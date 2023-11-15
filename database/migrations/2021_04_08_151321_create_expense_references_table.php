<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_expense');
            $table->foreign('id_expense')->references('id')->on('expenses');
            $table->string('code_type_doc', 2);
            $table->string('number', 50);
            $table->timestamp('date_issue');
            $table->string('code', 2);
            $table->string('reason', 180);
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
        Schema::dropIfExists('expense_references');
    }
}
