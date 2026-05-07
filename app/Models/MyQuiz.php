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
        'folder_id',
        'title',
        'description',
        'major_id',
        'course_id',
        'lecturer_id',
        'time_limit_minutes',
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

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id', 'id_lecturer');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'quiz_tags', 'quiz_id', 'tag_id');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id_folder');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id_user');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id', 'id_major');
    }

    public function createSnapshot(): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            $snapshotId = \Illuminate\Support\Facades\DB::table('quiz_snapshots')->insertGetId([
                'quiz_id' => $this->id_quiz,
                'version_number' => $this->version_number,
                'created_at' => now(),
            ]);

            $questions = $this->questions()->with('options')->get();

            foreach ($questions as $question) {
                $snapshotQuestionId = \Illuminate\Support\Facades\DB::table('snapshot_questions')->insertGetId([
                    'snapshot_id' => $snapshotId,
                    'original_question_id' => $question->id_question,
                    'content' => $question->content,
                    'question_type' => $question->question_type,
                    'order_index' => $question->order_index,
                    'explanation' => $question->explanation,
                ]);

                foreach ($question->options as $option) {
                    \Illuminate\Support\Facades\DB::table('snapshot_options')->insert([
                        'snapshot_question_id' => $snapshotQuestionId,
                        'original_option_id' => $option->id_option,
                        'content' => $option->content,
                        'is_correct' => $option->is_correct,
                        'order_index' => $option->order_index,
                    ]);
                }
            }
        });
    }
}