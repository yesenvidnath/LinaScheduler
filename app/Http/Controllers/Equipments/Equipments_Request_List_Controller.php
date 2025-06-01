<?php

namespace App\Http\Controllers\Admin\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\EquipmentRequestList;
use Illuminate\Http\Request;

class EquipmentRequestListController extends Controller
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
        return EquipmentRequestList::with(['course', 'equipment'])
            ->where('Is_Deleted', false)
            ->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Course_ID' => 'required|exists:Course,Course_ID',
            'Equip_ID' => 'required|exists:Equipment,Equip_ID',
            'Class_Type' => 'required|string',
            'Expected_Student_Count' => 'required|integer|min:1',
        ]);

        $validated['Is_Deleted'] = false;
        return EquipmentRequestList::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return EquipmentRequestList::with(['course', 'equipment'])
                ->where('Is_Deleted', false)
                ->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return EquipmentRequestList::with(['course', 'equipment'])
                    ->where('Is_Deleted', false)
                    ->whereBetween('ERL_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $request = EquipmentRequestList::with(['course', 'equipment'])
                ->find($param);
            if (!$request || $request->Is_Deleted) {
                return response()->json(['message' => 'Equipment request not found'], 404);
            }
            return $request;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, EquipmentRequestList $equipmentRequestList)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($equipmentRequestList->Is_Deleted) {
            return response()->json(['message' => 'Equipment request not found'], 404);
        }

        $validated = $request->validate([
            'Course_ID' => 'sometimes|required|exists:Course,Course_ID',
            'Equip_ID' => 'sometimes|required|exists:Equipment,Equip_ID',
            'Class_Type' => 'sometimes|required|string',
            'Expected_Student_Count' => 'sometimes|required|integer|min:1',
        ]);

        $equipmentRequestList->update($validated);
        return $equipmentRequestList->load(['course', 'equipment']);
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentRequestList::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All equipment requests marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentRequestList::whereBetween('ERL_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Equipment requests from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $request = EquipmentRequestList::find($param);
            if (!$request || $request->Is_Deleted) {
                return response()->json(['message' => 'Equipment request not found'], 404);
            }
            $request->Is_Deleted = true;
            $request->save();
            return response()->json(['message' => 'Equipment request marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentRequestList::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All equipment requests recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentRequestList::whereBetween('ERL_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Equipment requests from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $request = EquipmentRequestList::find($param);
            if (!$request) {
                return response()->json(['message' => 'Equipment request not found'], 404);
            }
            if (!$request->Is_Deleted) {
                return response()->json(['message' => 'Equipment request is not deleted'], 400);
            }
            $request->Is_Deleted = false;
            $request->save();
            return response()->json(['message' => 'Equipment request recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return EquipmentRequestList::with(['course', 'equipment'])
                ->where('Is_Deleted', true)
                ->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return EquipmentRequestList::with(['course', 'equipment'])
                    ->where('Is_Deleted', true)
                    ->whereBetween('ERL_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $request = EquipmentRequestList::with(['course', 'equipment'])
                ->where('ERL_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$request) {
                return response()->json(['message' => 'Deleted equipment request not found'], 404);
            }
            return $request;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
