<?php

namespace App\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'name', 'content', 'is_active'
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function results()
    {
        return $this->hasMany(QuestionnaireResult::class, 'questionnaire_id', 'id');
    }
}
