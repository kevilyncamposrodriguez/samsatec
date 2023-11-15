<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'symbol',
        'id_company',
    ];
}
