<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseOtherCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_expense',
        'type_document',
        'idcard',
        'name',
        'detail',
        'amount'
    ];
}
