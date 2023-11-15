<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleConditions extends Model
{
    use HasFactory;
    public static function serachByCode($code){
        return SaleConditions::where('code', $code)->first();
    }
}
