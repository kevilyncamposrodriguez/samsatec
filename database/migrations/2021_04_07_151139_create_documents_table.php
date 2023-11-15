<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company')->nullable();
            $table->foreign('id_company')->references('id')->on('teams')->onDelete('cascade');
            $table->unsignedBigInteger('id_branch_office')->nullable();
            $table->foreign('id_branch_office')->references('id')->on('branch_offices');
            $table->unsignedBigInteger('id_terminal')->nullable();
            $table->foreign('id_terminal')->references('id')->on('terminals');
            $table->unsignedBigInteger('id_client')->nullable();
            $table->foreign('id_client')->references('id')->on('clients')->onDelete('cascade');
            $table->string('key', 50)->nullable();
            $table->string('e_a', 6);
            $table->string('consecutive', 20);
            $table->timestamp('date_issue');
            $table->timestamp('delivery_date')->nullable();
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
            $table->double('total_exoneration', 18, 5);
            $table->double('total_tax', 18, 5);
            $table->double('total_other_charges', 18, 5);
            $table->double('iva_returned', 18, 5)->default(0);
            $table->double('total_cost', 18, 5)->default(0);
            $table->double('total_document', 18, 5)->default(0);
            $table->double('balance', 18, 5)->default(0);
            $table->integer('priority');
            $table->string('path')->nullable();
            $table->string('state_send')->default('Sin enviar');
            $table->string('answer_mh')->default('Ninguna');
            $table->string('detail_mh', 2000)->default('Ninguno');
            $table->string('type_doc', 2)->default('01');
            $table->string('note',3000)->nullable();
            $table->unsignedBigInteger('id_seller')->nullable();
            $table->foreign('id_seller')->references('id')->on('sellers');
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
        Schema::dropIfExists('documents');
    }
}
