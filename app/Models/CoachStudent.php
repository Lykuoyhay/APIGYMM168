<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachStudent extends Model
{
    protected $table = 'coach_students';

    protected $fillable = [
        'coach_id',
        'student_id',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
