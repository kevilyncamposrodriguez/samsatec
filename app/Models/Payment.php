<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'id_count',
        'id_company',
        'id_expense',
        'reference',
        'mount',
        'observations'
    ];
}
