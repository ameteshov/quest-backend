<?php

namespace App\Model;

class Role extends Model
{
    public const ROLE_ADMIN = 1;
    public const ROLE_USER = 2;

    public const DEFAULT_ROLE = self::ROLE_USER;

    protected $fillable = ['name'];
}
