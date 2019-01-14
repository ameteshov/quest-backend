<?php

namespace App\Model;

class QuestionnaireResult extends Model
{
    protected $table = 'questionnaires_results';

    protected $fillable = [
        'content', 'email', 'recipient_name',
        'access_hash', 'is_passed', 'questionnaire_id',
        'user_id', 'score', 'recipient_phone'
    ];

    protected $casts = ['content' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id', 'id');
    }
}
