<?php

namespace App\Model;

class Questionnaire extends Model
{
    public const AVG_TYPE = 'avg';
    public const SUM_TYPE = 'sum';

    protected $fillable = [
        'name', 'content', 'description', 'is_active', 'success_score', 'result_type', 'type_id', 'user_id'
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function results()
    {
        return $this->hasMany(QuestionnaireResult::class, 'questionnaire_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(QuestionnaireType::class, 'type_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
