<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesEconomicActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_economic_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_economic_activity');
            $table->foreign('id_economic_activity')->references('id')->on('economic_activities');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE companies_economic_activities ADD CONSTRAINT company_economic_activities_unique UNIQUE (id_economic_activity, id_company);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies_economic_activities');
    }
}
