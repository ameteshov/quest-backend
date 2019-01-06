<?php

namespace App\Model;

class Plan extends Model
{
    protected $fillable = [
        'id', 'name', 'price', 'points', 'is_active', 'description'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $casts = ['description' => 'array'];
}
