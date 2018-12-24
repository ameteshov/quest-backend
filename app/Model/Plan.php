<?php

namespace App\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'price', 'points',
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at'
    ];
}
