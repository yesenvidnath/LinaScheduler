<?php

namespace App\Http\Controllers\Admin\Users\WorkSchedules;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\WorkMode;
use Illuminate\Http\Request;

class WorkModeController extends Controller
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

        return WorkMode::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = WorkMode::where('Work_Mode_Name', $request->Work_Mode_Name)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A work mode with this name already exists',
                'field' => 'Work_Mode_Name',
            ], 422);
        }

        $validated = $request->validate([
            'Work_Mode_Name' => 'required|string|max:50',
            'Work_Mode_Description' => 'nullable|string',
        ]);

        $validated['Is_Deleted'] = false;

        return WorkMode::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return WorkMode::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return WorkMode::where('Is_Deleted', false)
                    ->whereBetween('Work_Mode_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $workMode = WorkMode::find($param);
            if (!$workMode || $workMode->Is_Deleted) {
                return response()->json(['message' => 'Work mode not found'], 404);
            }

            return $workMode;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, WorkMode $workMode)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($workMode->Is_Deleted) {
            return response()->json(['message' => 'Work mode not found'], 404);
        }

        if ($request->has('Work_Mode_Name')) {
            $exists = WorkMode::where('Work_Mode_Name', $request->Work_Mode_Name)
                ->where('Work_Mode_ID', '!=', $workMode->Work_Mode_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A work mode with this name already exists',
                    'field' => 'Work_Mode_Name',
                ], 422);
            }
        }

        $validated = $request->validate([
            'Work_Mode_Name' => 'sometimes|required|string|max:50',
            'Work_Mode_Description' => 'sometimes|nullable|string',
        ]);

        $workMode->update($validated);

        return $workMode;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            WorkMode::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All work modes marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                WorkMode::whereBetween('Work_Mode_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Work modes from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $workMode = WorkMode::find($param);
            if (!$workMode || $workMode->Is_Deleted) {
                return response()->json(['message' => 'Work mode not found'], 404);
            }

            $workMode->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'Work mode marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            WorkMode::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All work modes recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                WorkMode::whereBetween('Work_Mode_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Work modes from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $workMode = WorkMode::find($param);
            if (!$workMode) {
                return response()->json(['message' => 'Work mode not found'], 404);
            }

            if (!$workMode->Is_Deleted) {
                return response()->json(['message' => 'Work mode is not deleted'], 400);
            }

            $workMode->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'Work mode recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return WorkMode::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            [$start, $end] = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return WorkMode::where('Is_Deleted', true)
                    ->whereBetween('Work_Mode_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $workMode = WorkMode::where('Work_Mode_ID', $param)
                ->where('Is_Deleted', true)
                ->first();

            if (!$workMode) {
                return response()->json(['message' => 'Deleted work mode not found'], 404);
            }

            return $workMode;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
