<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'nature',
        'amount',
    ];
    public static function search($search){
        return empty($search)?static::query():static::where('nature','like','%'.$search.'%')
        ->orwhere('amount','like','%'.$search.'%')
        ->orwhere('create_at','like','%'.$search.'%');
    }
}
