<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MyQuiz;
use App\Models\User;

class Attempt extends Model
{
    protected $table = 'attempts';
    protected $primaryKey = 'id_attempt';

    public function quiz()
    {
        return $this->belongsTo(MyQuiz::class, 'quiz_id', 'id_quiz');
    }
}