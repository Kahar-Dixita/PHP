<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('student.index', compact('courses'));
    }

    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);

        $already = Enrollment::where('student_id', Auth::id())
                             ->where('course_id', $courseId)->first();

        if ($already) return back()->with('error', 'Already enrolled.');

        Enrollment::create([
            'student_id' => Auth::id(),
            'course_id' => $courseId,
            'enrolled_at' => now(),
        ]);

       return back()->with('success', 'Enrolled successfully!');

    }

    public function unenroll($courseId)
    {
        Enrollment::where('student_id', Auth::id())
                  ->where('course_id', $courseId)->delete();

        return back()->with('success', 'Unenrolled successfully.');
    }

    public function myCourses()
    {
        $courses = Auth::user()->coursesEnrolled;
        return view('student.mycourses', compact('courses'));
    }
}