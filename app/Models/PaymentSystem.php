<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSystem extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'id_company',
        'id_pay',
        'pay_key',
        'pay_amount',
        'pay_detail',
        'user',
        "fe",
        "months",
        "plan_id",
        "start_pay",
        "type_pay",
        "next_pay",
        "id_invoice"

    ];

}
