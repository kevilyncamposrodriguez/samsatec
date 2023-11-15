<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cantons extends Model
{
    use HasFactory;
    public static function serachByCode($idProvince, $code){
        return Cantons::where('id_province', $idProvince)
        ->where('code',$code)->first();
    }
}
