<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Provider extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'code',
        'id_card',
        'type_id_card',
        'name_provider',
        'id_province',
        'id_canton',
        'id_district',
        'other_signs',
        'id_country_code',
        'phone',
        'emails',
        'id_sale_condition',
        'time',
        'id_currency',
        'id_payment_method',
        'total_credit',
        'notes'
    ];
    public static function serachById($idCard)
    {
        return Provider::where('id_company', Auth::user()->currentTeam->id)
            ->where('id_card', $idCard)->first();
    }
    public static function getProvider($id)
    {
        return Provider::where('providers.id', '=', $id)
            ->join('provinces', 'provinces.id', '=', 'providers.id_province')
            ->join('cantons', 'cantons.id', '=', 'providers.id_canton')
            ->join('districts', 'districts.id', '=', 'providers.id_district')
            ->select('providers.*', 'cantons.canton', 'districts.district', 'provinces.province')->first();
    }
    public static function getCode()
    {
        return "Prov-" . str_pad(InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)
        ->first()->provider, 10, "0", STR_PAD_LEFT);
    }
    public static function nextCode()
    {
        $consecutive = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)
            ->first();
        $consecutive->update([
            "provider" => ($consecutive->provider + 1)
        ]);
    }
}
