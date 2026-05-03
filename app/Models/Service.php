<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'yoga_type',
        'benefits',
        'instructor_id',
        'price',
        'capacity',
        'duration',
        'currency',
        'images',
        'is_active',
        'class_schedule',
        'session_time',
        'pricing_options',
        'available_time_slots',
    ];

    protected $casts = [
        'benefits' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'class_schedule' => 'array',
        'pricing_options' => 'array',
        'available_time_slots' => 'array',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

}
