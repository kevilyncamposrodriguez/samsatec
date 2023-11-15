<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropboxFile extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getSizeInKbAttribute()
    {
        return number_format($this->size / 1024, 1);
    }
}
