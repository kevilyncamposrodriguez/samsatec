<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAjustmentsDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_ai',
        'id_product',
        'qty_start',
        'qty',
        'qty_end',
        'total_line',
        'cost'
    ];
}
