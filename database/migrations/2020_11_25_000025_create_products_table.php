<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // compania madre
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('teams');
            
            //codigo interno
            $table->unsignedBigInteger('id_class')->nullable()->default(null);
            $table->foreign('id_class')->references('id')->on('class_products');            
            $table->string('internal_code', 20)->nullable()->default(null); //agragar ceros
            //otro codigo
            $table->unsignedBigInteger('id_type_code_product')->nullable()->default(null);
            $table->foreign('id_type_code_product')->references('id')->on('type_code_products');            
            $table->string('other_code', 20)->nullable()->default(null); //agragar ceros
            
            // codigo cabys
            $table->string('cabys', 13); //agrega ceros
            // cuenta madre
            $table->unsignedBigInteger('id_count_income');
            $table->foreign('id_count_income')->references('id')->on('counts');
            // cuenta madre
            $table->unsignedBigInteger('id_count_expense');
            $table->foreign('id_count_expense')->references('id')->on('counts');
            // cuenta madre
            $table->unsignedBigInteger('id_count_inventory')->nullable()->default(null);
            $table->foreign('id_count_inventory')->references('id')->on('counts');
            //unidad de medida
            $table->unsignedBigInteger('id_sku');
            $table->foreign('id_sku')->references('id')->on('skuses');
            // nombre o descripcion del producto
            $table->string("description", 200);
            $table->json("ids_taxes")->nullable();
            $table->float("tax_base", 18, 5)->default(0);
            $table->float("export_tax", 18, 5)->default(0);
            //cantidad de bodega
            $table->integer('stock_start')->default(0);
            $table->integer('stock')->default(0);
            $table->float("price_unid", 18, 5)->default(0);
            $table->float("cost_unid", 18, 5)->default(0);
            $table->unsignedBigInteger('id_discount')->nullable();            
            $table->foreign('id_discount')->references('id')->on('discounts');
            $table->float("total_tax")->default(0);
            $table->float("stock_base")->default(0);
            $table->float("alert_min")->default(0);
            $table->float("total_price")->default(0);
            // familia madre
            $table->unsignedBigInteger('id_family')->nullable()->default(null);
            $table->foreign('id_family')->references('id')->on('families');
            // categoria madre
            $table->unsignedBigInteger('id_category')->nullable()->default(null);
            $table->foreign('id_category')->references('id')->on('categories');
            $table->integer("type")->default(0);
            $table->enum('active',[1,0])->default(1);
            $table->unsignedBigInteger('id_bo')->nullable();            
            $table->foreign('id_bo')->references('id')->on('branch_offices');
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
        Schema::dropIfExists('products');
    }
}
