<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $primaryKey = 'id_academic_year';

    protected $fillable = [
        'label',
        'start_date',
        'end_date',
        'is_active'
    ];
}
