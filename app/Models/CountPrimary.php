<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountPrimary extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'id_company',
        'id_count_category',
        'id_count_primary'
    ];
}
