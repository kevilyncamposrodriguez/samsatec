<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListsLists extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price',
        'id_price_list',
        'id_product',
    ];

    public function comrades()
    {
        return $this->hasManyThrough(PriceListsLists::class, Product::class, 'id', 'id_product', 'id_product', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
