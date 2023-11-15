<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayMethodInformation extends Model
{
    use HasFactory;
    protected $table = "pay_methods_information";
    protected $fillable = [
        'id',
        'count_number',
        'count_owner',
        'email_notification',
        'use_by_default',
        'id_team',
        'created_at',
        'updated_at',
        'active'
    ];
}
