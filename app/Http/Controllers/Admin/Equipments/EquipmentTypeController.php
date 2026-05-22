<?php

namespace App\Http\Controllers\Admin\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
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

        $equipmentTypes = EquipmentType::where('Is_Deleted', false)->get();

        if ($equipmentTypes->isEmpty()) {
            return response()->json(['message' => 'No equipment types found'], 404);
        }

        return $equipmentTypes;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = EquipmentType::where('Equip_Type', $request->Equip_Type)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'An equipment type with this name already exists',
                'field' => 'Equip_Type'
            ], 422);
        }

        $validated = $request->validate([
            'Equip_Type' => 'required|string|max:150',
            'Equip_Type_Discrption' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        return EquipmentType::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $equipmentTypes = EquipmentType::where('Is_Deleted', false)->get();
            if ($equipmentTypes->isEmpty()) {
                return response()->json(['message' => 'No equipment types found'], 404);
            }
            return $equipmentTypes;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $equipmentTypes = EquipmentType::where('Is_Deleted', false)
                    ->whereBetween('Equip_Type_ID', [$start, $end])
                    ->get();
                if ($equipmentTypes->isEmpty()) {
                    return response()->json(['message' => "No equipment types found in range $start-$end"], 404);
                }
                return $equipmentTypes;
            }
        }

        if (is_numeric($param)) {
            $equipmentType = EquipmentType::find($param);
            if (!$equipmentType || $equipmentType->Is_Deleted) {
                return response()->json(['message' => 'Equipment type not found'], 404);
            }
            return $equipmentType;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, EquipmentType $equipmenttype)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($equipmenttype->Is_Deleted) {
            return response()->json(['message' => 'Equipment type not found'], 404);
        }

        if ($request->has('Equip_Type')) {
            $exists = EquipmentType::where('Equip_Type', $request->Equip_Type)
                ->where('Equip_Type_ID', '!=', $equipmenttype->Equip_Type_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'An equipment type with this name already exists',
                    'field' => 'Equip_Type'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Equip_Type' => 'sometimes|required|string|max:150',
            'Equip_Type_Discrption' => 'sometimes|required|string'
        ]);

        $equipmenttype->update($validated);
        return $equipmenttype;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentType::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All equipment types marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentType::whereBetween('Equip_Type_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Equipment types from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $equipmentType = EquipmentType::find($param);
            if (!$equipmentType || $equipmentType->Is_Deleted) {
                return response()->json(['message' => 'Equipment type not found'], 404);
            }
            $equipmentType->Is_Deleted = true;
            $equipmentType->save();
            return response()->json(['message' => 'Equipment type marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentType::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All equipment types recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentType::whereBetween('Equip_Type_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Equipment types from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $equipmentType = EquipmentType::find($param);
            if (!$equipmentType) {
                return response()->json(['message' => 'Equipment type not found'], 404);
            }
            if (!$equipmentType->Is_Deleted) {
                return response()->json(['message' => 'Equipment type is not deleted'], 400);
            }
            $equipmentType->Is_Deleted = false;
            $equipmentType->save();
            return response()->json(['message' => 'Equipment type recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $equipmentTypes = EquipmentType::where('Is_Deleted', true)->get();
            if ($equipmentTypes->isEmpty()) {
                return response()->json(['message' => 'No deleted equipment types found'], 404);
            }
            return $equipmentTypes;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $equipmentTypes = EquipmentType::where('Is_Deleted', true)
                    ->whereBetween('Equip_Type_ID', [$start, $end])
                    ->get();
                if ($equipmentTypes->isEmpty()) {
                    return response()->json(['message' => "No deleted equipment types found in range $start-$end"], 404);
                }
                return $equipmentTypes;
            }
        }

        if (is_numeric($param)) {
            $equipmentType = EquipmentType::where('Equip_Type_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$equipmentType) {
                return response()->json(['message' => 'Deleted equipment type not found'], 404);
            }
            return $equipmentType;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

}
