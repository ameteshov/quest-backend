<?php

namespace App\Util;

use Laravel\Socialite\Contracts\User;

class SocialUser implements User
{
    protected $id;
    protected $nickname;
    protected $email;
    protected $avatar;
    protected $name;

    public function getId()
    {
        return $this->id;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getName()
    {
        return $this->name;
    }
}
