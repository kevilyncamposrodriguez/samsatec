<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_credit',
        'id_count',
        'month_payment',
        'capital_contribution',
        'loan_interest',
        'other_interest',
        'interest_late_payment',
        'safe',
        'endorsement',
        'policy',
        'saving',
        'other_saving',
        'total_fee',
        'date_pay'
    ];
}
