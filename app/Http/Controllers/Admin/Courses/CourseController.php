<?php

namespace App\Http\Controllers\Admin\Courses;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $adminVerifier;

    public function __construct(AdminVerificationController $adminVerifier)
    {
        $this->middleware('auth:sanctum');
        $this->adminVerifier = $adminVerifier;
    }

    public function index()
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $courses = Course::where('Is_Deleted', false)->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'No courses found'], 404);
        }

        return $courses;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Course::where('Course_Name', $request->Course_Name)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A course with this name already exists',
                'field' => 'Course_Name'
            ], 422);
        }

        $validated = $request->validate([
            'Course_Name' => 'required|string|max:100',
            'Course_Discription' => 'required|string',
            'Status' => 'required|in:1,0,1*'
        ]);

        $validated['Is_Deleted'] = false;
        return Course::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::where('Course_ID', $param)
            ->where('Is_Deleted', false)
            ->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return $course;
    }

    public function update(Request $request, $course)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::where('Course_ID', $course)
            ->where('Is_Deleted', false)
            ->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validated = $request->validate([
            'Course_Name' => 'required|string|max:100',
            'Course_Discription' => 'required|string',
            'Status' => 'required|in:1,0,1*'
        ]);

        $course->update($validated);
        return $course;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::where('Course_ID', $param)
            ->where('Is_Deleted', false)
            ->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->update(['Is_Deleted' => true]);
        return response()->json(['message' => 'Course deleted successfully']);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::where('Course_ID', $param)
            ->where('Is_Deleted', true)
            ->first();

        if (!$course) {
            return response()->json(['message' => 'Deleted course not found'], 404);
        }

        $course->update(['Is_Deleted' => false]);
        return response()->json(['message' => 'Course recovered successfully']);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course = Course::where('Course_ID', $param)
            ->where('Is_Deleted', true)
            ->first();

        if (!$course) {
            return response()->json(['message' => 'Deleted course not found'], 404);
        }

        return $course;
    }
}
