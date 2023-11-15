<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consecutive extends Model {
    use HasFactory;
    protected $fillable = [
    "id_branch_offices",
    'fe' ,
    'nc',
    'nd',
    'fc',
    'fex',
    'te',
    'mra',
    'mrr',
    'fci',
    'oc'
    ];
}
