<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderAccount extends Model
{
    protected $fillable = [
        'description',
        'account',
        'active',
        'id_provider',
        'sinpe'
    ];
    use HasFactory;
}
