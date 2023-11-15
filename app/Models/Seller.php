<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_company","id_card","type_id_card","name","id_province","id_canton","id_district",
        "other_signs","id_country_code","phone","emails","commission","active"
    ];
}
