<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTaxesCodesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('taxes_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2);
            $table->string('description');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE taxes_codes CHANGE  code code INT(2) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('taxes_codes');
    }

}
