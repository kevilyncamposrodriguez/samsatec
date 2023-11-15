<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'id_taxes_code',
        'id_rate_code',
        'rate',
        'description',
        'id_exoneration',
        'exoneration_amount',
        'tax_net',
    ];
}
