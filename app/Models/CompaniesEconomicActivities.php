<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniesEconomicActivities extends Model
{
    use HasFactory;
    protected $fillable = [
        "id", "id_company", "id_economic_activity"
    ];
}
