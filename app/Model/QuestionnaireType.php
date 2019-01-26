<?php

namespace App\Model;

class QuestionnaireType extends Model
{
    public $timestamps = false;

    protected $table = 'questionnaire_types';

    protected $fillable = ['name'];

    public function questionnaire()
    {
        return $this->hasOne(Questionnaire::class, 'type_id', 'id');
    }
}
