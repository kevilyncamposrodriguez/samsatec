<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateConsecutivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consecutives', function (Blueprint $table) {
           $table->bigIncrements('id');
           $table->unsignedBigInteger('id_terminal');
            $table->foreign('id_terminal')->references('id')->on('terminals');
            $table->unsignedBigInteger('id_branch_offices');
            $table->foreign('id_branch_offices')->references('id')->on('branch_offices');
            $table->integer("c_fe")->default('1');
            $table->integer("c_nc")->default('1');
            $table->integer("c_nd")->default('1');
            $table->integer("c_fc")->default('1');
            $table->integer("c_fex")->default('1');
            $table->integer("c_te")->default('1');
            $table->integer("c_mra")->default('1');
            $table->integer("c_mrr")->default('1');
            $table->integer("pay")->default('1');
            $table->integer("c_fci")->default('1');
            $table->integer("c_oc")->default('1');
            $table->integer("c_ov")->default('1');
            $table->integer("c_fi")->default('1');
            $table->integer("c_co")->default('1');              
            $table->timestamps();
        });
        DB::statement('ALTER TABLE consecutives CHANGE  c_fe c_fe INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_nc c_nc INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_nd c_nd INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_fc c_fc INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_fex c_fex INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_te c_te INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_mra c_mra INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_mrr c_mrr INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  pay pay INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_fci c_fci INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_oc c_oc INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_ov c_ov INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_fi c_fi INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
        DB::statement('ALTER TABLE consecutives CHANGE  c_co c_co INT(10) UNSIGNED ZEROFILL NOT NULL DEFAULT(1)');
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consecutives');
    }
}
