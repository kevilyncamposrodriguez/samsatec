<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_count_d');
            $table->foreign('id_count_d')->references('id')->on('counts');
            $table->unsignedBigInteger('id_count_c');
            $table->foreign('id_count_c')->references('id')->on('counts');
            $table->string('user');
            $table->string('detail');
            $table->timestamp('date_issue');
            $table->string('reference');
            $table->double('mount', 18, 5);
            $table->string('consecutive',11);
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
        Schema::dropIfExists('transfers');
    }
}
