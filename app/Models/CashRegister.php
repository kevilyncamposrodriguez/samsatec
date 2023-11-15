<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'id_terminal',
        'sales',
        'bills',
        'start_balance',
        'end_balance',
        'state',
        'observation',
        'finish',
        'id_user'
    ];
    use HasFactory;
}
