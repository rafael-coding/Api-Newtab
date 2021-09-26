<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profession',
        'localization',
        'level',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];
}
