<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'id_count',
        'id_company',
        'id_document',
        'reference',
        'mount',
        'path',
        'observations',
        'payment_method'
    ];
}
