<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company')->nullable();            
            $table->foreign('id_company')->references('id')->on('teams')->onDelete('cascade');     
            $table->unsignedBigInteger('id_count')->nullable();            
            $table->foreign('id_count')->references('id')->on('counts')->onDelete('cascade');
            $table->unsignedBigInteger('id_document');
            $table->foreign('id_document')->references('id')->on('documents');
            $table->string('reference');
            $table->double('mount',18,5);
            $table->string('observations');
            $table->timestamp('date');
            $table->string('path')->default('');
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
        Schema::dropIfExists('payment_invoices');
    }
}
