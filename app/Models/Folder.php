<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MyQuiz;

class Folder extends Model
{
    protected $table = 'folders';
    protected $primaryKey = 'id_folder';
    
    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function quizzes()
    {
        return $this->hasMany(MyQuiz::class, 'folder_id', 'id_folder');
    }
}
