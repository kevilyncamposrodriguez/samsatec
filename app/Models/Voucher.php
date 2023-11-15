<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'id_provider',
        'key',
        'consecutive',
        'currency',
        'exchange_rate',
        'subtotal',
        'total_discount',
        'total_tax',
        'total_exoneration',
        'other_charges',
        'total_invoice',
        'condition',
        'e_a',
        'detail_mh',
        'state',
        'state_send',
        'ruta',
        'pendingCE'
    ];
}
