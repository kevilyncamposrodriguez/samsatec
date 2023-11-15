<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInternalConsecutivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_consecutives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->integer("c_v")->default('1');
            $table->integer("c_t")->default('1');
            $table->integer("provider")->default('1');
            $table->integer("client")->default('1');
            $table->integer("ai")->default('1');
            $table->integer("ii")->default('1');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE internal_consecutives CHANGE  c_v c_v INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE internal_consecutives CHANGE  c_t c_t INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE internal_consecutives CHANGE  provider provider INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE internal_consecutives CHANGE  client client INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_consecutives');
    }
}
