<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_company","id_card","type_id_card","name_client","id_province","id_canton","id_district","code","notes",
        "other_signs","id_country_code","phone","emails","id_sale_condition","time","id_currency","id_payment_method","id_count","id_price_list","total_credit"
    ];
    public static function getCode()
    {
        return "Clie-" . str_pad(InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)
        ->first()->client, 10, "0", STR_PAD_LEFT);
    }
    public static function nextCode()
    {
        $consecutive = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)
            ->first();
        $consecutive->update([
            "client" => ($consecutive->client + 1)
        ]);
    }
}
