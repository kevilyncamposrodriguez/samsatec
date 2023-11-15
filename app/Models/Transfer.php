<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'id_count_c',
        'id_count_d',
        'id_company',
        'detail',
        'date_issue',
        'reference',
        'mount',
        'consecutive'
    ];
}
