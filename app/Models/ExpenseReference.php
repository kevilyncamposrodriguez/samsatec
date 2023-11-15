<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseReference extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_expense',
        'code_type_doc',
        'number',
        'date_issue',
        'code',
        'reason'
    ];
}
