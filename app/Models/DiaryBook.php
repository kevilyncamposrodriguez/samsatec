<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryBook extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry',
        'third',
        'document',
        'code',
        'id_count',
        'debit',
        'credit',
        'id_company',
        'id_bo',
        'terminal',
        'id_user',
        'name_user'
    ];
}
