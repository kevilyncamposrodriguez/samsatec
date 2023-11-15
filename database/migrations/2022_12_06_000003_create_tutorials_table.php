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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->string('description');
            $table->string('doc');
            $table->unsignedBigInteger('id_subcategory');
            $table->foreign('id_subcategory')->references('id')->on('tutorial_subcategories');
            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')->references('id')->on('tutorial_categories');
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
        Schema::dropIfExists('tutorials');
    }
};
