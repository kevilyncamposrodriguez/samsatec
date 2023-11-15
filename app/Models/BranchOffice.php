<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model {

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'name_branch_office',
        'number',
        'id_province',
        'id_canton',
        'id_count',
        'id_district',
        'other_signs',
        'emails',
        'id_country_code',
        'phone'
    ];

}
