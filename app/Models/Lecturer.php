<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $primaryKey = 'id_lecturer';

    protected $fillable = [
        'staff_number',
        'full_name',
        'email',
        'major_id'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id', 'id_major');
    }
}
