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
        Schema::create('inventory_ajustments_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ai');  
            $table->foreign('id_ai')->references('id')->on('inventory_ajustments');
            $table->unsignedBigInteger('id_product');  
            $table->foreign('id_product')->references('id')->on('products');
            $table->float('qty_start',18,5);
            $table->float('qty',18,5);
            $table->float('qty_end',18,5);
            $table->float('cost',18,5);
            $table->float('total_line',18,5);
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
        Schema::dropIfExists('inventory_ajustments_details');
    }
};
