<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'financial_entity',
        'credit_number',
        'credit_mount',
        'date_issue',
        'formalization_expenses',
        'credit_rate',
        'period',
        'currency',
        'taxed',
        'other_expenses',
        'savings',
        'pay_day'
    ];
    use HasFactory;
}
