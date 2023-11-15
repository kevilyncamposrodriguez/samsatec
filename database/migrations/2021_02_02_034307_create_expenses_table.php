<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            $table->unsignedBigInteger('id_provider');
            $table->foreign('id_provider')->references('id')->on('providers');
            $table->unsignedBigInteger('id_branch_office');
            $table->foreign('id_branch_office')->references('id')->on('branch_offices')->onDelete('cascade');
            $table->unsignedBigInteger('id_buyer')->nullable();
            $table->foreign('id_buyer')->references('id')->on('buyers');
            $table->string('key', 50);
            $table->string('e_a', 6);
            $table->string('consecutive', 20);
            $table->string('consecutive_real', 20);
            $table->dateTime('date_issue');
            $table->dateTime('expiration_date')->nullable();
            $table->dateTime('possible_deliver_date')->nullable();
            $table->dateTime('deliver_date')->nullable();
            $table->string('sale_condition', 2);
            $table->string('term', 10);
            $table->string('payment_method', 2);
            $table->string('currency', 3);
            $table->double('exchange_rate', 18, 5);
            $table->unsignedBigInteger('id_mh_categories')->nullable();
            $table->foreign('id_mh_categories')->references('id')->on('mh_categories')->onDelete('cascade');
            $table->double('total_taxed_services', 18, 5)->default(0);
            $table->double('total_exempt_services', 18, 5)->default(0);
            $table->double('total_exonerated_services', 18, 5)->default(0);
            $table->double('total_taxed_merchandise', 18, 5)->default(0);
            $table->double('total_exempt_merchandise', 18, 5)->default(0);
            $table->double('total_exonerated_merchandise', 18, 5)->default(0);
            $table->double('total_taxed', 18, 5)->default(0);
            $table->double('total_exempt', 18, 5)->default(0);
            $table->double('total_exonerated', 18, 5)->default(0);
            $table->double('total_discount', 18, 5);
            $table->double('total_net_sale', 18, 5);
            $table->double('total_tax', 18, 5);
            $table->double('total_other_charges', 18, 5);
            $table->double('total_document', 18, 5);
            $table->float("pending_amount", 18, 5)->default(0);
            $table->string('condition');
            $table->unsignedBigInteger('id_count')->nullable();
            $table->foreign('id_count')->references('id')->on('counts')->onDelete('cascade');
            $table->enum('type_purchase', ['Sin clasificar', 'Compra', 'Gasto'])->default("Sin clasificar");
            $table->string('state');
            $table->string('ruta');
            $table->enum('state_pay', ['Pendiente', 'Pago parcial', 'Pagado'])->default('Pendiente');
            $table->string('mh_detail',2000)->default('Ninguno');
            $table->integer("pendingCE")->default(0);
            $table->string('type_doc');
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
        Schema::dropIfExists('expenses');
    }
}
