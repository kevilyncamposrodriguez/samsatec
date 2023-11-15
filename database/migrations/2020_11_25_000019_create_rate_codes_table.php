<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code',2);
            $table->string('description');
            $table->integer('value');
            $table->timestamps();
        });
         DB::statement('ALTER TABLE rate_codes CHANGE  code code INT(2) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_codes');
    }
}
