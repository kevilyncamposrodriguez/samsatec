<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exoneration extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'description',
        'id_type_document_exoneration',
        'document_number',
        'institutional_name',
        'date',
        'exemption_percentage',
        'active'
    ];
}
