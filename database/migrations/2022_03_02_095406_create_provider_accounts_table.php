<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_provider');
            $table->foreign('id_provider')->references('id')->on('providers');
            $table->string('description');
            $table->enum('active',[0,1])->default(1);
            $table->string('account');
            $table->string('sinpe',8);
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
        Schema::dropIfExists('provider_accounts');
    }
}
