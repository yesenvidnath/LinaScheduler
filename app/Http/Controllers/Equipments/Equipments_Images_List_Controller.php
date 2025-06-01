<?php

namespace App\Http\Controllers\Admin\Equipments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\EquipmentImage;
use Illuminate\Http\Request;

class EquipmentImageController extends Controller
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
        return EquipmentImage::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Equip_ID' => 'required|exists:Equipment,Equip_ID',
            'EQI_Image' => 'required|string',
            'EQI_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        return EquipmentImage::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return EquipmentImage::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return EquipmentImage::where('Is_Deleted', false)
                    ->whereBetween('EQI_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::find($param);
            if (!$image || $image->Is_Deleted) {
                return response()->json(['message' => 'Equipment image not found'], 404);
            }
            return $image;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, EquipmentImage $equipmentimage)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($equipmentimage->Is_Deleted) {
            return response()->json(['message' => 'Equipment image not found'], 404);
        }

        $validated = $request->validate([
            'Equip_ID' => 'sometimes|required|exists:Equipment,Equip_ID',
            'EQI_Image' => 'sometimes|required|string',
            'EQI_Discription' => 'sometimes|required|string'
        ]);

        $equipmentimage->update($validated);
        return $equipmentimage;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentImage::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All equipment images marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentImage::whereBetween('EQI_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Equipment images from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::find($param);
            if (!$image || $image->Is_Deleted) {
                return response()->json(['message' => 'Equipment image not found'], 404);
            }
            $image->Is_Deleted = true;
            $image->save();
            return response()->json(['message' => 'Equipment image marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            EquipmentImage::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All equipment images recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                EquipmentImage::whereBetween('EQI_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Equipment images from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::find($param);
            if (!$image) {
                return response()->json(['message' => 'Equipment image not found'], 404);
            }
            if (!$image->Is_Deleted) {
                return response()->json(['message' => 'Equipment image is not deleted'], 400);
            }
            $image->Is_Deleted = false;
            $image->save();
            return response()->json(['message' => 'Equipment image recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return EquipmentImage::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return EquipmentImage::where('Is_Deleted', true)
                    ->whereBetween('EQI_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $image = EquipmentImage::where('EQI_ID', $param)
                        ->where('Is_Deleted', true)
                        ->first();
            if (!$image) {
                return response()->json(['message' => 'Deleted equipment image not found'], 404);
            }
            return $image;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
