<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentOtherCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_document',
        'type_document',
        'idcard',
        'name',
        'detail',
        'amount'
    ];
}
