<?php

namespace App\Http\Controllers\Admin\RoomTypes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\RoomClass;
use Illuminate\Http\Request;

class RoomClassController extends Controller
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

        $classes = RoomClass::where('Is_Deleted', false)->with('room')->get();

        if ($classes->isEmpty()) {
            return response()->json(['message' => 'No classes found'], 404);
        }

        return $classes;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = RoomClass::where('Cls_Number', $request->Cls_Number)
            ->where('Room_ID', $request->Room_ID)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A class with this number already exists in this room',
                'field' => 'Cls_Number'
            ], 422);
        }

        $validated = $request->validate([
            'Room_ID' => 'required|exists:Rooms,Room_ID',
            'Cls_Number' => 'required|string|max:50',
            'Cls_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        $class = RoomClass::create($validated);
        return $class->load('room');
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $classes = RoomClass::where('Is_Deleted', false)->with('room')->get();
            if ($classes->isEmpty()) {
                return response()->json(['message' => 'No classes found'], 404);
            }
            return $classes;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $classes = RoomClass::where('Is_Deleted', false)
                    ->whereBetween('Cls_ID', [$start, $end])
                    ->with('room')
                    ->get();
                if ($classes->isEmpty()) {
                    return response()->json(['message' => "No classes found in range $start-$end"], 404);
                }
                return $classes;
            }
        }

        if (is_numeric($param)) {
            $class = RoomClass::with('room')->find($param);
            if (!$class || $class->Is_Deleted) {
                return response()->json(['message' => 'Class not found'], 404);
            }
            return $class;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, RoomClass $class)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($class->Is_Deleted) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        if ($request->has('Cls_Number')) {
            $exists = RoomClass::where('Cls_Number', $request->Cls_Number)
                ->where('Room_ID', $request->Room_ID ?? $class->Room_ID)
                ->where('Cls_ID', '!=', $class->Cls_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A class with this number already exists in this room',
                    'field' => 'Cls_Number'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Room_ID' => 'sometimes|required|exists:Rooms,Room_ID',
            'Cls_Number' => 'sometimes|required|string|max:50',
            'Cls_Discription' => 'sometimes|required|string'
        ]);

        $class->update($validated);
        return $class->load('room');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            RoomClass::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All classes marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                RoomClass::whereBetween('Cls_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Classes from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $class = RoomClass::find($param);
            if (!$class || $class->Is_Deleted) {
                return response()->json(['message' => 'Class not found'], 404);
            }
            $class->Is_Deleted = true;
            $class->save();
            return response()->json(['message' => 'Class marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            RoomClass::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All classes recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                RoomClass::whereBetween('Cls_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Classes from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $class = RoomClass::find($param);
            if (!$class) {
                return response()->json(['message' => 'Class not found'], 404);
            }
            if (!$class->Is_Deleted) {
                return response()->json(['message' => 'Class is not deleted'], 400);
            }
            $class->Is_Deleted = false;
            $class->save();
            return response()->json(['message' => 'Class recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $classes = RoomClass::where('Is_Deleted', true)->with('room')->get();
            if ($classes->isEmpty()) {
                return response()->json(['message' => 'No deleted classes found'], 404);
            }
            return $classes;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $classes = RoomClass::where('Is_Deleted', true)
                    ->whereBetween('Cls_ID', [$start, $end])
                    ->with('room')
                    ->get();
                if ($classes->isEmpty()) {
                    return response()->json(['message' => "No deleted classes found in range $start-$end"], 404);
                }
                return $classes;
            }
        }

        if (is_numeric($param)) {
            $class = RoomClass::with('room')
                    ->where('Cls_ID', $param)
                    ->where('Is_Deleted', true)
                    ->first();
            if (!$class) {
                return response()->json(['message' => 'Deleted class not found'], 404);
            }
            return $class;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
