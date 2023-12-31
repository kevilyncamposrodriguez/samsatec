<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryCodes extends Model
{
    use HasFactory;
    public static function serachByCode($code){
        return CountryCodes::where('phone_code', $code)->first();
    }
}
