<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReference extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_document',
        'code_type_doc',
        'number',
        'date_issue',
        'code',
        'reason'
    ];
}
