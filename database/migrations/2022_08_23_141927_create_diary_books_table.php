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
        Schema::create('diary_books', function (Blueprint $table) {
            $table->id();
            $table->string('entry',100);
            $table->string('third');
            $table->integer('document');
            $table->string('code');
            $table->unsignedBigInteger('id_count');            
            $table->foreign('id_count')->references('id')->on('counts');
            $table->float('qty',18,5);
            $table->float('debit',18,5);
            $table->float('credit',18,5);
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_bo');            
            $table->foreign('id_bo')->references('id')->on('branch_offices');
            $table->string('terminal');
            $table->unsignedBigInteger('id_user');            
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('name_user');
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
        Schema::dropIfExists('diary_books');
    }
};
