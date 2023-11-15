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
        Schema::create('inventory_ajustments', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutive');
            $table->unsignedBigInteger('id_company');  
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_count');            
            $table->foreign('id_count')->references('id')->on('counts');            
            $table->string('observation',1000);
            $table->float('total',18,5);
            $table->string('type');//Traslado,Ajuste
            $table->unsignedBigInteger('id_terminal');  
            $table->foreign('id_terminal')->references('id')->on('terminals');
            $table->string('nameuser');
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
        Schema::dropIfExists('inventory_ajustments');
    }
};
