<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAjustment extends Model
{
    use HasFactory;
    protected $fillable = [
        'consecutive',
        'id_company',
        'id_count',
        'total',
        'observation',
        'type',
        'id_terminal',
        'nameuser',
        'total'
    ];
}
