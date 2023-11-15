<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalConsecutive extends Model
{
    use HasFactory;
    protected $fillable = [
        'c_v',
        'c_t',
        'provider',
        'client',
        'id_company',
        'ai',
        'ii'
    ];
}
