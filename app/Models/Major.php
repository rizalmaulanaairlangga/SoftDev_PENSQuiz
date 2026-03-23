<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $primaryKey = 'id_major';

    protected $fillable = ['code', 'name'];

    public function users()
    {
        return $this->hasMany(User::class, 'major_id', 'id_major');
    }
}