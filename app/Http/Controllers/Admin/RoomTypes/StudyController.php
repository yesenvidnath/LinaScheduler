<?php

namespace App\Http\Controllers\Admin\RoomTypes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Study;
use Illuminate\Http\Request;

class StudyController extends Controller
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

        $studies = Study::where('Is_Deleted', false)->with('room')->get();

        if ($studies->isEmpty()) {
            return response()->json(['message' => 'No study rooms found'], 404);
        }

        return $studies;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Study::where('Study_Number', $request->Study_Number)
            ->where('Room_ID', $request->Room_ID)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A study room with this number already exists in this room',
                'field' => 'Study_Number'
            ], 422);
        }

        $validated = $request->validate([
            'Room_ID' => 'required|exists:Rooms,Room_ID',
            'Study_Number' => 'required|string|max:50',
            'Study_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        $study = Study::create($validated);
        return $study->load('room');
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $studies = Study::where('Is_Deleted', false)->with('room')->get();
            if ($studies->isEmpty()) {
                return response()->json(['message' => 'No study rooms found'], 404);
            }
            return $studies;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $studies = Study::where('Is_Deleted', false)
                    ->whereBetween('Study_ID', [$start, $end])
                    ->with('room')
                    ->get();
                if ($studies->isEmpty()) {
                    return response()->json(['message' => "No study rooms found in range $start-$end"], 404);
                }
                return $studies;
            }
        }

        if (is_numeric($param)) {
            $study = Study::with('room')->find($param);
            if (!$study || $study->Is_Deleted) {
                return response()->json(['message' => 'Study room not found'], 404);
            }
            return $study;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Study $study)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($study->Is_Deleted) {
            return response()->json(['message' => 'Study room not found'], 404);
        }

        if ($request->has('Study_Number')) {
            $exists = Study::where('Study_Number', $request->Study_Number)
                ->where('Room_ID', $request->Room_ID ?? $study->Room_ID)
                ->where('Study_ID', '!=', $study->Study_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A study room with this number already exists in this room',
                    'field' => 'Study_Number'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Room_ID' => 'sometimes|required|exists:Rooms,Room_ID',
            'Study_Number' => 'sometimes|required|string|max:50',
            'Study_Discription' => 'sometimes|required|string'
        ]);

        $study->update($validated);
        return $study->load('room');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Study::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All study rooms marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Study::whereBetween('Study_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Study rooms from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $study = Study::find($param);
            if (!$study || $study->Is_Deleted) {
                return response()->json(['message' => 'Study room not found'], 404);
            }
            $study->Is_Deleted = true;
            $study->save();
            return response()->json(['message' => 'Study room marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Study::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All study rooms recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Study::whereBetween('Study_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Study rooms from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $study = Study::find($param);
            if (!$study) {
                return response()->json(['message' => 'Study room not found'], 404);
            }
            if (!$study->Is_Deleted) {
                return response()->json(['message' => 'Study room is not deleted'], 400);
            }
            $study->Is_Deleted = false;
            $study->save();
            return response()->json(['message' => 'Study room recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $studies = Study::where('Is_Deleted', true)->with('room')->get();
            if ($studies->isEmpty()) {
                return response()->json(['message' => 'No deleted study rooms found'], 404);
            }
            return $studies;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $studies = Study::where('Is_Deleted', true)
                    ->whereBetween('Study_ID', [$start, $end])
                    ->with('room')
                    ->get();
                if ($studies->isEmpty()) {
                    return response()->json(['message' => "No deleted study rooms found in range $start-$end"], 404);
                }
                return $studies;
            }
        }

        if (is_numeric($param)) {
            $study = Study::with('room')
                    ->where('Study_ID', $param)
                    ->where('Is_Deleted', true)
                    ->first();
            if (!$study) {
                return response()->json(['message' => 'Deleted study room not found'], 404);
            }
            return $study;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
