<?php

namespace App\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'token', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
