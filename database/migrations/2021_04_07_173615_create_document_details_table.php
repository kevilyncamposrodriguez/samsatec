<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_document')->nullable();  
            $table->foreign('id_document')->references('id')->on('documents');
            $table->unsignedBigInteger('id_product')->nullable();  
            $table->foreign('id_product')->references('id')->on('products');   
            $table->string('tariff_heading',12);   
            $table->string('code',13);
            $table->double('qty',16,3); 
            $table->double('qty_dispatch',16,3); 
            $table->string('sku',15);
            $table->string('detail',200);
            $table->double('price_unid',18,5); 
            $table->float("cost_unid", 18, 5)->default(0); 
            $table->double('total_amount',18,5); 
            $table->json('discounts');   
            $table->double('subtotal',18,5);
            $table->json('taxes'); 
            $table->double('tax_net',18,5);   
            $table->double('total_amount_line',18,5); 
            $table->unsignedBigInteger('id_count')->nullable();            
            $table->foreign('id_count')->references('id')->on('counts')->onDelete('cascade');
            $table->string('note',1000);
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
        Schema::dropIfExists('document_details');
    }
}
