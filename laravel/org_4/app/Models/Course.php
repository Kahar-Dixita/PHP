<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
     protected $fillable = ['teacher_id', 'title', 'description', 'duration'];


    // Each course belongs to one teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // A course has many students through enrollments
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
                    ->withPivot('enrolled_at');
    }
}
