<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentOtherChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_other_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_document')->nullable();  
            $table->foreign('id_document')->references('id')->on('documents');  
            $table->string('type_document',2);   
            $table->string('idcard',6);
            $table->string('name',100);
            $table->string('detail',160);
            $table->double('amount',18,5);    
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
        Schema::dropIfExists('document_other_charges');
    }
}
