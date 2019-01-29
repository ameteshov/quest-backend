<?php

namespace App\Model;

class Plan extends Model
{
    public const PURCHASE_TYPE = 'purchase';
    public const SUB_TYPE = 'subscription';

    protected $fillable = [
        'id', 'name', 'price', 'points', 'is_active', 'description', 'type'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $casts = ['description' => 'array'];
}
