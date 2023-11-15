<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->string('name');
            $table->string('description');
            $table->unsignedBigInteger('id_count_primary');
            $table->foreign('id_count_primary')->references('id')->on('count_primaries');
            $table->unsignedBigInteger('id_count')->nullable();
            $table->foreign('id_count')->references('id')->on('counts');
            $table->enum('is_deleted', [0, 1])->default("1");
            $table->double('initial_balance', 18, 5)->default(0);
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
        Schema::dropIfExists('counts');
    }
}
