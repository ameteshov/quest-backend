<?php

namespace App\Model;

class EmployeeCharacteristic extends Model
{
    protected $fillable = [
        'name'
    ];

    public function questionnaire()
    {
        return $this->hasOne(Questionnaire::class, 'characteristic_id', 'id');
    }
}
