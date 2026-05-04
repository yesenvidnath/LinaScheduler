<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
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
        return Branch::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check for duplicate branch name
        $exists = Branch::where('Branch_Name', $request->Branch_Name)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A branch with this name already exists',
                'field' => 'Branch_Name'
            ], 422);
        }

        $validated = $request->validate([
            'Branch_Name' => 'required|string|max:100',
            'Branch_Discription' => 'required|string',
            'Status' => 'required|in:1,0,1*'
        ]);

        $validated['Is_Deleted'] = false;
        return Branch::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Handle all branches case
        if ($param === '*') {
            return Branch::where('Is_Deleted', false)->get();
        }

        // Handle range case
        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Branch::where('Is_Deleted', false)
                    ->whereBetween('Branch_ID', [$start, $end])
                    ->get();
            }
        }

        // Handle single ID case
        if (is_numeric($param)) {
            $branch = Branch::find($param);
            if (!$branch || $branch->Is_Deleted) {
                return response()->json(['message' => 'Branch not found'], 404);
            }
            return $branch;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Branch $branch)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($branch->Is_Deleted) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        // Check for duplicate branch name, excluding current branch
        if ($request->has('Branch_Name')) {
            $exists = Branch::where('Branch_Name', $request->Branch_Name)
                ->where('Branch_ID', '!=', $branch->Branch_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A branch with this name already exists',
                    'field' => 'Branch_Name'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Branch_Name' => 'sometimes|required|string|max:100',
            'Branch_Discription' => 'sometimes|required|string',
            'Status' => 'sometimes|required|in:1,0,1*'
        ]);

        $branch->update($validated);
        return $branch;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Handle all branches case
        if ($param === '*') {
            Branch::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All branches marked as deleted successfully']);
        }

        // Handle range case
        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Branch::whereBetween('Branch_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Branches from $start to $end marked as deleted successfully"]);
            }
        }

        // Handle single ID case
        if (is_numeric($param)) {
            $branch = Branch::find($param);
            if (!$branch || $branch->Is_Deleted) {
                return response()->json(['message' => 'Branch not found'], 404);
            }
            $branch->Is_Deleted = true;
            $branch->save();
            return response()->json(['message' => 'Branch marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Handle all deleted branches case
        if ($param === '*') {
            Branch::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All branches recovered successfully']);
        }

        // Handle range case
        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Branch::whereBetween('Branch_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Branches from $start to $end recovered successfully"]);
            }
        }

        // Handle single ID case
        if (is_numeric($param)) {
            $branch = Branch::find($param);
            if (!$branch) {
                return response()->json(['message' => 'Branch not found'], 404);
            }
            if (!$branch->Is_Deleted) {
                return response()->json(['message' => 'Branch is not deleted'], 400);
            }
            $branch->Is_Deleted = false;
            $branch->save();
            return response()->json(['message' => 'Branch recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Handle all deleted branches case
        if ($param === '*') {
            return Branch::where('Is_Deleted', true)->get();
        }

        // Handle range case
        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Branch::where('Is_Deleted', true)
                    ->whereBetween('Branch_ID', [$start, $end])
                    ->get();
            }
        }

        // Handle single ID case
        if (is_numeric($param)) {
            $branch = Branch::where('Branch_ID', $param)
                        ->where('Is_Deleted', true)
                        ->first();
            if (!$branch) {
                return response()->json(['message' => 'Deleted branch not found'], 404);
            }
            return $branch;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
