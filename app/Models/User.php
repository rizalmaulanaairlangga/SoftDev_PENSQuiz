<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Major;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id_user';

    public $incrementing = true;

    protected $keyType = 'int';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'major_id',
        'year_of_entry',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id', 'id_major');
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
