<?php

namespace App\Http\Controllers\Admin\RoomTypes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\LaboratoryType;
use Illuminate\Http\Request;

class LaboratoryTypeController extends Controller
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

        $labTypes = LaboratoryType::where('Is_Deleted', false)->get();

        if ($labTypes->isEmpty()) {
            return response()->json(['message' => 'No laboratory types found'], 404);
        }

        return $labTypes;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = LaboratoryType::where('Lab_Type', $request->Lab_Type)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A laboratory type with this name already exists',
                'field' => 'Lab_Type'
            ], 422);
        }

        $validated = $request->validate([
            'Lab_Type' => 'required|string|max:100',
            'Lab_Type_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        return LaboratoryType::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $labTypes = LaboratoryType::where('Is_Deleted', false)->get();
            if ($labTypes->isEmpty()) {
                return response()->json(['message' => 'No laboratory types found'], 404);
            }
            return $labTypes;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $labTypes = LaboratoryType::where('Is_Deleted', false)
                    ->whereBetween('Lab_Type_ID', [$start, $end])
                    ->get();
                if ($labTypes->isEmpty()) {
                    return response()->json(['message' => "No laboratory types found in range $start-$end"], 404);
                }
                return $labTypes;
            }
        }

        if (is_numeric($param)) {
            $labType = LaboratoryType::find($param);
            if (!$labType || $labType->Is_Deleted) {
                return response()->json(['message' => 'Laboratory type not found'], 404);
            }
            return $labType;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, LaboratoryType $labtype)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($labtype->Is_Deleted) {
            return response()->json(['message' => 'Laboratory type not found'], 404);
        }

        if ($request->has('Lab_Type')) {
            $exists = LaboratoryType::where('Lab_Type', $request->Lab_Type)
                ->where('Lab_Type_ID', '!=', $labtype->Lab_Type_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A laboratory type with this name already exists',
                    'field' => 'Lab_Type'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Lab_Type' => 'sometimes|required|string|max:100',
            'Lab_Type_Discription' => 'sometimes|required|string'
        ]);

        $labtype->update($validated);
        return $labtype;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            LaboratoryType::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All laboratory types marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                LaboratoryType::whereBetween('Lab_Type_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Laboratory types from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $labType = LaboratoryType::find($param);
            if (!$labType || $labType->Is_Deleted) {
                return response()->json(['message' => 'Laboratory type not found'], 404);
            }
            $labType->Is_Deleted = true;
            $labType->save();
            return response()->json(['message' => 'Laboratory type marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            LaboratoryType::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All laboratory types recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                LaboratoryType::whereBetween('Lab_Type_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Laboratory types from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $labType = LaboratoryType::find($param);
            if (!$labType) {
                return response()->json(['message' => 'Laboratory type not found'], 404);
            }
            if (!$labType->Is_Deleted) {
                return response()->json(['message' => 'Laboratory type is not deleted'], 400);
            }
            $labType->Is_Deleted = false;
            $labType->save();
            return response()->json(['message' => 'Laboratory type recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $labTypes = LaboratoryType::where('Is_Deleted', true)->get();
            if ($labTypes->isEmpty()) {
                return response()->json(['message' => 'No deleted laboratory types found'], 404);
            }
            return $labTypes;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $labTypes = LaboratoryType::where('Is_Deleted', true)
                    ->whereBetween('Lab_Type_ID', [$start, $end])
                    ->get();
                if ($labTypes->isEmpty()) {
                    return response()->json(['message' => "No deleted laboratory types found in range $start-$end"], 404);
                }
                return $labTypes;
            }
        }

        if (is_numeric($param)) {
            $labType = LaboratoryType::where('Lab_Type_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$labType) {
                return response()->json(['message' => 'Deleted laboratory type not found'], 404);
            }
            return $labType;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
