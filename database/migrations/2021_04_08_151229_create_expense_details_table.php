<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_expense');
            $table->foreign('id_expense')->references('id')->on('expenses');
            $table->string("tariff_heading", 12)->default(0);
            $table->integer('line');
            $table->string("cabys", 13);
            $table->double("qty",16,3);
            $table->string("sku",15);
            $table->string("detail", 200);
            $table->double("price", 18, 5)->default(0);
            $table->double('total_amount',18,5); 
            $table->json('discounts')->nullable();   
            $table->double('subtotal',18,5);
            $table->json('taxes')->nullable(); 
            $table->double('tax_net',18,5);   
            $table->integer('type')->default(0);
            $table->double('total_amount_line',18,5);
            $table->unsignedBigInteger('id_count')->nullable();
            $table->foreign('id_count')->references('id')->on('counts')->onDelete('cascade');
            $table->unsignedBigInteger('id_product')->nullable();
            $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('expense_details');
    }
}
