<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_lists_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_price_list');            
            $table->foreign('id_price_list')->references('id')->on('price_lists');
            $table->unsignedBigInteger('id_product');            
            $table->foreign('id_product')->references('id')->on('products');
            $table->double('price')->default(0);
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
        Schema::dropIfExists('price_lists_lists');
    }
}
