<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Question;
use App\Models\Attempt;
use App\Models\Course;

class MyQuiz extends Model
{
    use SoftDeletes;
    
    protected $table = 'quizzes';
    protected $primaryKey = 'id_quiz';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'major_id',
        'course_id',
        'lecturer_id',
        'academic_year_id',
        'class_id',
        'semester',
        'visibility',
        'access',
        'allow_copy',
        'version_number',
        'has_been_updated',
        'cover_image_url',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id', 'id_quiz');
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class, 'quiz_id', 'id_quiz');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id_course');
    }
}