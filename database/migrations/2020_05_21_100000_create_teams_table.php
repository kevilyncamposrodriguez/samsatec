<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('id_card')->nullable();
            $table->unsignedBigInteger('type_id_card')->nullable();
            $table->foreign('type_id_card')->references('id')->on('type_id_cards');
            $table->string('name');
            $table->string('user_mh')->nullable();
            $table->string('pass_mh')->nullable();
            $table->string('cryptographic_key')->nullable();
            $table->string('pin')->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('personal_team');
            $table->enum('active', [0,1])->default("1");
            $table->enum('proof', [0,1])->default("0");
            $table->enum('fe', [0,1])->default("1");
            $table->string('email_company')->nullable();
            $table->string('phone_company')->nullable();
            $table->string('accounts')->nullable();
            $table->string('ridivi_username')->nullable();
            $table->string('ridivi_pass')->nullable();
            $table->string('ridivi_key')->nullable();
            $table->string('ridivi_secret')->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->foreign('referred_by')->references('id')->on('teams');
            $table->string('referral_code')->nullable();
            $table->enum('bo_inventory', [0,1])->default("0");
            $table->enum('cash_register', [0,1])->default("0");
            $table->foreignId('plan_id')->nullable();
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
        Schema::dropIfExists('teams');
    }
}
