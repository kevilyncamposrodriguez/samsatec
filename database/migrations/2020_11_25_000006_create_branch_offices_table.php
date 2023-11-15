<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBranchOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->integer('number');            
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('teams');
            $table->string('name_branch_office');
            $table->unsignedBigInteger('id_province');
            $table->foreign('id_province')->references('id')->on('provinces');
            $table->unsignedBigInteger('id_canton');
            $table->foreign('id_canton')->references('id')->on('cantons');
            $table->unsignedBigInteger('id_district');
            $table->foreign('id_district')->references('id')->on('districts');
            $table->string('other_signs');
            $table->unsignedBigInteger('id_country_code');
            $table->foreign('id_country_code')->references('id')->on('country_codes');
            $table->unsignedBigInteger('id_count');//inventary
            $table->foreign('id_count')->references('id')->on('counts');
            $table->string('phone',8);
            $table->string('emails');
            $table->enum('active', [0,1])->default('1')->nullable();         
            $table->timestamps();
        });
        DB::statement('ALTER TABLE branch_offices ADD CONSTRAINT company_branch_offices_unique UNIQUE (id_company, number);');
        DB::statement('ALTER TABLE branch_offices CHANGE  number number INT(3) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_offices');
    }
}
