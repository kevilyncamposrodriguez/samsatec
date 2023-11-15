<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTypeDocumentOtherChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_document_other_charges', function (Blueprint $table) {
            $table->id();
            $table->string("type_document");
            $table->integer('code');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE type_document_other_charges CHANGE  code code INT(2) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_document_other_charges');
    }
}
