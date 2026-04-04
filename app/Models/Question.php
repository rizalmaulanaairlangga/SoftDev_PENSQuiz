<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use SoftDeletes;
    
    use HasFactory;

    protected $table = 'questions';
    protected $primaryKey = 'id_question';

    protected $fillable = [
        'quiz_id',
        'content',
        'question_type',
        'order_index',
        'explanation',
    ];

    public function quiz()
    {
        return $this->belongsTo(MyQuiz::class, 'quiz_id', 'id_quiz');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'question_id', 'id_question');
    }
}