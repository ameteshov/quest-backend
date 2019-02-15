<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = ['password', 'vk_id', 'google_id', 'facebook_id'];

    protected $guarded = [
        'questionnaires_count', 'is_active', 'reset_token', 'role_id', 'points', 'plan_id', 'subscribed_before',
        'vk_id', 'google_id', 'facebook_id', 'twitter_id', 'odnoklassniki_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
