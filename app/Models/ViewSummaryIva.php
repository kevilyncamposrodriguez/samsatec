<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewSummaryIva extends Model
{
    protected $fillable = [
        'Mes', 'Compania', 'Actividad_Economica',
    ];
    use HasFactory;
}
