<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','id_company', 'internal_code', 'cabys', 'id_count', 'id_sku', 'description', 'ids_taxes', 'tax_base', 'export_tax', 'price_unid', 'cost_unid','tax_base',
        'total_price', 'id_family', 'id_category', 'stock_base', 'alert_min', 'id_class', 'stock','id_count_income','id_count_expense','id_count_inventory', 
        'type','other_code','id_type_code_product','id_discount','stock_start'
    ];
    public static function nextConseutive($id)
    {
        $cons = ClassProduct::where('class_products.id', $id)->first();
        $consecutive["consecutive"] = ($cons->consecutive + 1);
        ClassProduct::where('class_products.id', '=', $id)->update($consecutive);
    }
}
