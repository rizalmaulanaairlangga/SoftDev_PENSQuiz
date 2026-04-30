<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tag';
    protected $fillable = ['name'];

    public function quizzes()
    {
        return $this->belongsToMany(MyQuiz::class, 'quiz_tags', 'tag_id', 'quiz_id');
    }
}
