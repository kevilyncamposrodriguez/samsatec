<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorialCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'link',
        'description',
        'doc',
        'tipo',
        'id_category',
        'id_subcategory',
    ];
}
