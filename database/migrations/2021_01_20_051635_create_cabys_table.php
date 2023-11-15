<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabys', function (Blueprint $table) {
            $table->id();
            $table->string('categoria_1');
            $table->string('descripcion_categoria_1');
            $table->string('categoria_2');
            $table->string('descripcion_categoria_2');
            $table->string('categoria_3');
            $table->string('descripcion_categoria_3');
            $table->string('categoria_4');
            $table->string('descripcion_categoria_4');
            $table->string('categoria_5');
            $table->string('descripcion_categoria_5');
            $table->string('categoria_6');
            $table->string('descripcion_categoria_6');
            $table->string('categoria_7');
            $table->string('descripcion_categoria_7');
            $table->string('categoria_8');
            $table->string('descripcion_categoria_8');
            $table->string('codigo');
            $table->string('descripcion');
            $table->string('impuesto');
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
        Schema::dropIfExists('cabys');
    }
}
