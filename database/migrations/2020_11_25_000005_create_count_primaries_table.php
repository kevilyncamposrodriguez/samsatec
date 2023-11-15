<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountPrimariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('count_primaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('id_count_category');
            $table->foreign('id_count_category')->references('id')->on('count_categories');
            $table->unsignedBigInteger('id_count_primary')->nullable();
            $table->foreign('id_count_primary')->references('id')->on('count_primaries');
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
        Schema::dropIfExists('count_primaries');
    }
}
