<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'profession',
        'experience',
        'bio',
        'specialities',
        'certifications',
        'image',
    ];

    protected $casts = [
        'certifications' => 'array',
    ];
}
