<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_document')->nullable();  
            $table->foreign('id_document')->references('id')->on('documents');    
            $table->string('code_type_doc',2);   
            $table->string('number',50);
            $table->timestamp('date_issue');
            $table->string('code',2);
            $table->string('reason',180);   
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
        Schema::dropIfExists('document_references');
    }
}
