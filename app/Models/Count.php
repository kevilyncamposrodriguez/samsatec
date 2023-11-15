<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Count extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'id_company',
        'id_count_primary',
        'id_count',
        'initial_balance',
        'balance'
    ];
    public static function getAccounts($id, &$result=[])
    {
        $counts = Count::where("counts.id_count", $id)->get();

        if (count($counts) > 0) {
            foreach ($counts as $key => $value) {
                Count::getAccounts($value->id, $result);
            }
        }else{
            array_push($result,Count::find($id));
        }
        return $result;
    }
}
