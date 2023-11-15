<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paybills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_count');
            $table->foreign('id_count')->references('id')->on('counts');
            $table->string('name');
            $table->string('idcard');
            $table->string('detail');
            $table->timestamp('date_issue');
            $table->string('reference');
            $table->integer('term');
            $table->double('mount', 18, 5);
            $table->string('consecutive',11);
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
        Schema::dropIfExists('paybills');
    }
}
