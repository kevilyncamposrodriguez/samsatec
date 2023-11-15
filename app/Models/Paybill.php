<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paybill extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'id_count',
        'id_company',
        'idcard',
        'detail',
        'date_issue',
        'reference',
        'term',
        'mount',
        'consecutive'
    ];
}
