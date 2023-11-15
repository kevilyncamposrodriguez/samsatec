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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_terminal');
            $table->foreign('id_terminal')->references('id')->on('terminals');
            $table->float('sales', 18, 5)->default(0);
            $table->float('bills', 18, 5)->default(0);
            $table->float('start_balance', 18, 5)->default(0);
            $table->float('end_balance', 18, 5)->default(0);
            $table->enum('state', [0, 1])->default(1);
            $table->string('observation', 1000);
            $table->timestamp('finish')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users');
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
        Schema::dropIfExists('cash_registers');
    }
};
