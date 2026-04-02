<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';
    protected $primaryKey = 'id_option';

    protected $fillable = [
        'question_id',
        'content',
        'is_correct',
        'order_index',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id_question');
    }
}