<?php

namespace App\Http\Controllers\Admin\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
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

        $equipment = Equipment::where('Is_Deleted', false)
            ->with('equipmentType')
            ->get();

        if ($equipment->isEmpty()) {
            return response()->json(['message' => 'No equipment found'], 404);
        }

        return $equipment;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Equip_Type_ID' => 'required|exists:EquipmentType,Equip_Type_ID',
            'Equip_Discrption' => 'required|string',
            'Equip_Userbility_Status' => 'required|in:1,0,1*',
            'Is_Booked' => 'required|in:1,0,1*'
        ]);

        $validated['Is_Deleted'] = false;
        $equipment = Equipment::create($validated);
        return $equipment->load('equipmentType');
    }

    // Add remaining CRUD methods following same pattern
}
