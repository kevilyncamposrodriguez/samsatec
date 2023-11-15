<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'description',
        'descriptionJSON',
        'price_for_month',
        'created_at',
        'updated_at',
        'active'
    ];
}
