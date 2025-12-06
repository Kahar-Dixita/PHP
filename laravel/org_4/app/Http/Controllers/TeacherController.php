<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $courses = Auth::user()->courses;
        return view('teacher.index', compact('courses'));
    }

    public function create()
    {
        return view('teacher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable|string',
            'duration' => 'required|string',
        ]);

        Course::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'duration' => $request->duration,
        ]);

        return redirect()->route('teacher.index')->with('success', 'Course created!');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('teacher.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        if ($course->teacher_id !== Auth::id()) abort(403);

        $course->update($request->only('title', 'description', 'duration'));
        return redirect()->route('teacher.index')->with('success', 'Updated successfully!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        if ($course->teacher_id !== Auth::id()) abort(403);
        $course->delete();

        return redirect()->route('teacher.index')->with('success', 'Deleted!');
    }

    public function showStudents($id)
    {
        $course = Course::findOrFail($id);
        if ($course->teacher_id !== Auth::id()) abort(403);
        $students = $course->students;
        return view('teacher.students', compact('course', 'students'));
    }
}
