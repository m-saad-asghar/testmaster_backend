<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerModel extends Model
{
    use HasFactory;

    protected $table = 'answers';

    public function examQuestion()
    {
        return $this->belongsTo(QuestionModel::class, 'question_id', 'id');
    }
}
