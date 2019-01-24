<?php

namespace App\Model;

class Questionnaire extends Model
{
    public const AVG_TYPE = 'avg';
    public const SUM_TYPE = 'sum';

    protected $fillable = [
        'name', 'content', 'description', 'is_active', 'success_score', 'type', 'characteristic_id'
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function results()
    {
        return $this->hasMany(QuestionnaireResult::class, 'questionnaire_id', 'id');
    }

    public function characteristic()
    {
        return $this->belongsTo(EmployeeCharacteristic::class, 'characteristic_id', 'id');
    }
}
