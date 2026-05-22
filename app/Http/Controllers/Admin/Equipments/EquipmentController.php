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
            'Equip_Type_ID' => 'required|exists:Equipment_Types,Equip_Type_ID',
            'Equip_Discrption' => 'required|string',
            'Equip_Userbility_Status' => 'required|in:1,0,1*',
            'Is_Booked' => 'required|in:1,0,1*'
        ]);

        $validated['Is_Deleted'] = false;
        $equipment = Equipment::create($validated);
        return $equipment->load('equipmentType');
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $equipment = Equipment::where('Is_Deleted', false)
                ->with('equipmentType')
                ->get();

            if ($equipment->isEmpty()) {
                return response()->json(['message' => 'No equipment found'], 404);
            }

            return $equipment;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $equipment = Equipment::where('Is_Deleted', false)
                    ->whereBetween('Equip_ID', [$start, $end])
                    ->with('equipmentType')
                    ->get();

                if ($equipment->isEmpty()) {
                    return response()->json(['message' => "No equipment found in range $start-$end"], 404);
                }

                return $equipment;
            }
        }

        if (is_numeric($param)) {
            $equipment = Equipment::with('equipmentType')->find($param);
            if (!$equipment || $equipment->Is_Deleted) {
                return response()->json(['message' => 'Equipment not found'], 404);
            }

            return $equipment;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Equipment $equipment)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($equipment->Is_Deleted) {
            return response()->json(['message' => 'Equipment not found'], 404);
        }

        $validated = $request->validate([
            'Equip_Type_ID' => 'sometimes|required|exists:Equipment_Types,Equip_Type_ID',
            'Equip_Discrption' => 'sometimes|required|string',
            'Equip_Userbility_Status' => 'sometimes|required|in:1,0,1*',
            'Is_Booked' => 'sometimes|required|in:1,0,1*'
        ]);

        $equipment->update($validated);
        return $equipment->load('equipmentType');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Equipment::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All equipment marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Equipment::whereBetween('Equip_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Equipment from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $equipment = Equipment::find($param);
            if (!$equipment || $equipment->Is_Deleted) {
                return response()->json(['message' => 'Equipment not found'], 404);
            }

            $equipment->Is_Deleted = true;
            $equipment->save();
            return response()->json(['message' => 'Equipment marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Equipment::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All equipment recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Equipment::whereBetween('Equip_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Equipment from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $equipment = Equipment::find($param);
            if (!$equipment) {
                return response()->json(['message' => 'Equipment not found'], 404);
            }
            if (!$equipment->Is_Deleted) {
                return response()->json(['message' => 'Equipment is not deleted'], 400);
            }

            $equipment->Is_Deleted = false;
            $equipment->save();
            return response()->json(['message' => 'Equipment recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $equipment = Equipment::where('Is_Deleted', true)
                ->with('equipmentType')
                ->get();

            if ($equipment->isEmpty()) {
                return response()->json(['message' => 'No deleted equipment found'], 404);
            }

            return $equipment;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $equipment = Equipment::where('Is_Deleted', true)
                    ->whereBetween('Equip_ID', [$start, $end])
                    ->with('equipmentType')
                    ->get();

                if ($equipment->isEmpty()) {
                    return response()->json(['message' => "No deleted equipment found in range $start-$end"], 404);
                }

                return $equipment;
            }
        }

        if (is_numeric($param)) {
            $equipment = Equipment::with('equipmentType')
                ->where('Equip_ID', $param)
                ->where('Is_Deleted', true)
                ->first();

            if (!$equipment) {
                return response()->json(['message' => 'Deleted equipment not found'], 404);
            }

            return $equipment;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
