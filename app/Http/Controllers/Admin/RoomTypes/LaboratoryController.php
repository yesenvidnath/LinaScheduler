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
            'Room_ID' => 'required|exists:Rooms,Room_ID',
            'Lab_Type_ID' => 'required|exists:laboratory_types,Lab_Type_ID',
            'Lab_Number' => 'required|string|max:50',
            'Lab_Equipment_Count' => 'required|integer|min:0',
            'Lab_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        $laboratory = Laboratory::create($validated);
        return $laboratory->load(['room', 'laboratoryType']);
    }

    // ... remaining CRUD methods follow the same pattern as StudyController
}
