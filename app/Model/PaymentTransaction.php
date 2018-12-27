<?php

namespace App\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id', 'payment_id', 'plan_id', 'token', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'payment_id', 'id');
    }
}
