<?php

namespace App\Http\Controllers\Admin\RoomTypes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
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

        $laboratories = Laboratory::where('Is_Deleted', false)
            ->with(['room', 'laboratoryType'])
            ->get();

        if ($laboratories->isEmpty()) {
            return response()->json(['message' => 'No laboratories found'], 404);
        }

        return $laboratories;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Laboratory::where('Lab_Number', $request->Lab_Number)
            ->where('Room_ID', $request->Room_ID)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A laboratory with this number already exists in this room',
                'field' => 'Lab_Number'
            ], 422);
        }

        $validated = $request->validate([
            'Room_ID' => 'required|exists:rooms,Room_ID',
            'Lab_Type_ID' => 'required|exists:laboratory_types,Lab_Type_ID',
            'Lab_Number' => 'required|string|max:50',
            'Lab_Equipment_Count' => 'required|integer|min:0',
            'Lab_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        $laboratory = Laboratory::create($validated);
        return $laboratory->load(['room', 'laboratoryType']);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $laboratories = Laboratory::where('Is_Deleted', false)
                ->with(['room', 'laboratoryType'])
                ->get();
            if ($laboratories->isEmpty()) {
                return response()->json(['message' => 'No laboratories found'], 404);
            }
            return $laboratories;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $laboratories = Laboratory::where('Is_Deleted', false)
                    ->whereBetween('Lab_ID', [$start, $end])
                    ->with(['room', 'laboratoryType'])
                    ->get();
                if ($laboratories->isEmpty()) {
                    return response()->json(['message' => "No laboratories found in range $start-$end"], 404);
                }
                return $laboratories;
            }
        }

        if (is_numeric($param)) {
            $laboratory = Laboratory::with(['room', 'laboratoryType'])->find($param);
            if (!$laboratory || $laboratory->Is_Deleted) {
                return response()->json(['message' => 'Laboratory not found'], 404);
            }
            return $laboratory;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Laboratory $laboratory)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($laboratory->Is_Deleted) {
            return response()->json(['message' => 'Laboratory not found'], 404);
        }

        if ($request->has('Lab_Number')) {
            $exists = Laboratory::where('Lab_Number', $request->Lab_Number)
                ->where('Room_ID', $request->Room_ID ?? $laboratory->Room_ID)
                ->where('Lab_ID', '!=', $laboratory->Lab_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A laboratory with this number already exists in this room',
                    'field' => 'Lab_Number'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Room_ID' => 'sometimes|required|exists:rooms,Room_ID',
            'Lab_Type_ID' => 'sometimes|required|exists:laboratory_types,Lab_Type_ID',
            'Lab_Number' => 'sometimes|required|string|max:50',
            'Lab_Equipment_Count' => 'sometimes|required|integer|min:0',
            'Lab_Discription' => 'sometimes|required|string'
        ]);

        $laboratory->update($validated);
        return $laboratory->load(['room', 'laboratoryType']);
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Laboratory::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All laboratories marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Laboratory::whereBetween('Lab_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Laboratories from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $laboratory = Laboratory::find($param);
            if (!$laboratory || $laboratory->Is_Deleted) {
                return response()->json(['message' => 'Laboratory not found'], 404);
            }
            $laboratory->Is_Deleted = true;
            $laboratory->save();
            return response()->json(['message' => 'Laboratory marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Laboratory::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All laboratories recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Laboratory::whereBetween('Lab_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Laboratories from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $laboratory = Laboratory::find($param);
            if (!$laboratory) {
                return response()->json(['message' => 'Laboratory not found'], 404);
            }
            if (!$laboratory->Is_Deleted) {
                return response()->json(['message' => 'Laboratory is not deleted'], 400);
            }
            $laboratory->Is_Deleted = false;
            $laboratory->save();
            return response()->json(['message' => 'Laboratory recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $laboratories = Laboratory::where('Is_Deleted', true)
                ->with(['room', 'laboratoryType'])
                ->get();
            if ($laboratories->isEmpty()) {
                return response()->json(['message' => 'No deleted laboratories found'], 404);
            }
            return $laboratories;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $laboratories = Laboratory::where('Is_Deleted', true)
                    ->whereBetween('Lab_ID', [$start, $end])
                    ->with(['room', 'laboratoryType'])
                    ->get();
                if ($laboratories->isEmpty()) {
                    return response()->json(['message' => "No deleted laboratories found in range $start-$end"], 404);
                }
                return $laboratories;
            }
        }

        if (is_numeric($param)) {
            $laboratory = Laboratory::with(['room', 'laboratoryType'])
                ->where('Lab_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$laboratory) {
                return response()->json(['message' => 'Deleted laboratory not found'], 404);
            }
            return $laboratory;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
