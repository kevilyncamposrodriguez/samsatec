<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_expense',
        'line',
        'tariff_heading',
        'cabys',
        'qty',
        'sku',
        'detail',
        'type',
        'price',
        'total_amount',  
        'discounts',
        'subtotal',
        'taxes',
        'tax_net',
        'total_amount_line',
        'id_product',
        'id_count',
        'mh_detail'
    ];
}
