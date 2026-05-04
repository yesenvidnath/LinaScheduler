<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Flow;
use Illuminate\Http\Request;

class FlowController extends Controller
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
        return Flow::where('Is_Deleted', false)->with('branch')->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check for duplicate flow name within the same branch
        $exists = Flow::where('Fl_Name', $request->Fl_Name)
            ->where('Branch_ID', $request->Branch_ID)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A flow with this name already exists in this branch',
                'field' => 'Fl_Name'
            ], 422);
        }

        $validated = $request->validate([
            'Branch_ID' => 'required|exists:Branches,Branch_ID',
            'Fl_Name' => 'required|string|max:100',
            'Fl_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        return Flow::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Flow::where('Is_Deleted', false)->with('branch')->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Flow::where('Is_Deleted', false)
                    ->whereBetween('Fl_ID', [$start, $end])
                    ->with('branch')
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $flow = Flow::with('branch')->find($param);
            if (!$flow || $flow->Is_Deleted) {
                return response()->json(['message' => 'Flow not found'], 404);
            }
            return $flow;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Flow $flow)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($flow->Is_Deleted) {
            return response()->json(['message' => 'Flow not found'], 404);
        }

        if ($request->has('Fl_Name')) {
            $exists = Flow::where('Fl_Name', $request->Fl_Name)
                ->where('Branch_ID', $request->Branch_ID ?? $flow->Branch_ID)
                ->where('Fl_ID', '!=', $flow->Fl_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A flow with this name already exists in this branch',
                    'field' => 'Fl_Name'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Branch_ID' => 'sometimes|required|exists:Branches,Branch_ID',
            'Fl_Name' => 'sometimes|required|string|max:100',
            'Fl_Discription' => 'sometimes|required|string'
        ]);

        $flow->update($validated);
        return $flow->load('branch');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Flow::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All flows marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Flow::whereBetween('Fl_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Flows from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $flow = Flow::find($param);
            if (!$flow || $flow->Is_Deleted) {
                return response()->json(['message' => 'Flow not found'], 404);
            }
            $flow->Is_Deleted = true;
            $flow->save();
            return response()->json(['message' => 'Flow marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Flow::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All flows recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Flow::whereBetween('Fl_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Flows from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $flow = Flow::find($param);
            if (!$flow) {
                return response()->json(['message' => 'Flow not found'], 404);
            }
            if (!$flow->Is_Deleted) {
                return response()->json(['message' => 'Flow is not deleted'], 400);
            }
            $flow->Is_Deleted = false;
            $flow->save();
            return response()->json(['message' => 'Flow recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Flow::where('Is_Deleted', true)->with('branch')->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Flow::where('Is_Deleted', true)
                    ->whereBetween('Fl_ID', [$start, $end])
                    ->with('branch')
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $flow = Flow::with('branch')
                    ->where('Fl_ID', $param)
                    ->where('Is_Deleted', true)
                    ->first();
            if (!$flow) {
                return response()->json(['message' => 'Deleted flow not found'], 404);
            }
            return $flow;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
