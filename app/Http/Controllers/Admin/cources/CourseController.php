<?php

namespace App\Http\Controllers\Admin\cources;

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

        return Course::where('Is_Deleted', false)->get();
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

        if ($param === '*') {
            return Course::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Course::where('Is_Deleted', false)
                    ->whereBetween('Course_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $course = Course::find($param);
            if (!$course || $course->Is_Deleted) {
                return response()->json(['message' => 'Course not found'], 404);
            }
            return $course;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Course $course)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($course->Is_Deleted) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        if ($request->has('Course_Name')) {
            $exists = Course::where('Course_Name', $request->Course_Name)
                ->where('Course_ID', '!=', $course->Course_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A course with this name already exists',
                    'field' => 'Course_Name'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Course_Name' => 'sometimes|required|string|max:100',
            'Course_Discription' => 'sometimes|required|string',
            'Status' => 'sometimes|required|in:1,0,1*'
        ]);

        $course->update($validated);
        return $course;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Course::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All courses marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Course::whereBetween('Course_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Courses from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $course = Course::find($param);
            if (!$course || $course->Is_Deleted) {
                return response()->json(['message' => 'Course not found'], 404);
            }
            $course->Is_Deleted = true;
            $course->save();
            return response()->json(['message' => 'Course marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Course::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All courses recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Course::whereBetween('Course_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Courses from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $course = Course::find($param);
            if (!$course) {
                return response()->json(['message' => 'Course not found'], 404);
            }
            if (!$course->Is_Deleted) {
                return response()->json(['message' => 'Course is not deleted'], 400);
            }
            $course->Is_Deleted = false;
            $course->save();
            return response()->json(['message' => 'Course recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Course::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Course::where('Is_Deleted', true)
                    ->whereBetween('Course_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $course = Course::where('Course_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$course) {
                return response()->json(['message' => 'Deleted course not found'], 404);
            }
            return $course;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
