<?php

namespace App\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'price', 'points', 'is_active', 'description'
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = ['description' => 'array'];
}
