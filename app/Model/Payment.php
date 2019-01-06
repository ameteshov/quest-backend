<?php

namespace App\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'payment_id', 'status', 'is_paid',
        'amount', 'currency', 'description', 'started_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
