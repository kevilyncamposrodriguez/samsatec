<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewTotalSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'ANO',
        'COMPANIA'
    ];
}
