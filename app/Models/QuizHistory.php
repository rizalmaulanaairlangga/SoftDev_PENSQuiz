<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizHistory extends Model
{
    protected $table = 'quiz_histories';
    protected $primaryKey = 'id_history';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'last_opened_at'
    ];

    protected $casts = [
        'last_opened_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function quiz()
    {
        return $this->belongsTo(MyQuiz::class, 'quiz_id', 'id_quiz');
    }
}
