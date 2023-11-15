<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->integer('number');   
            $table->integer('active')->default('1');   
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_branch_office');            
            $table->foreign('id_branch_office')->references('id')->on('branch_offices')->onDelete('cascade');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `terminals` ADD UNIQUE `unique_index`(`id_company`, `id_branch_office`, `number`);');
        DB::statement('ALTER TABLE terminals CHANGE  number number INT(5) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminals');
    }
}
