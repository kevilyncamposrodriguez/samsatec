<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_document',
        'id_product',
        'tariff_heading',
        'code',
        'qty',
        'qty_dispatch',
        'sku',
        'detail',
        'price_unid',
        'cost_unid',
        'total_amount',
        'discounts',
        'subtotal',
        'taxes',
        'tax_net',
        'total_amount_line',
        'symbol',
        'id_count',
        'note'
    ];
}
