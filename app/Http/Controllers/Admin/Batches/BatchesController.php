<?php

namespace App\Http\Controllers\Admin\Batches;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Batches;
use Illuminate\Http\Request;

class BatchesController extends Controller
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

        return Batches::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Batches::where('Batch_Name', $request->Batch_Name)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A batch with this name already exists',
                'field' => 'Batch_Name'
            ], 422);
        }

        $validated = $request->validate([
            'Batch_Name' => 'required|string|max:100',
            'Batch_Student_Count' => 'required|integer',
            'Batch_Discription' => 'required|string',
            'Status' => 'required|in:1,0,1*'
        ]);

        $validated['Is_Deleted'] = false;
        return Batches::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Batches::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Batches::where('Is_Deleted', false)
                    ->whereBetween('Batch_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $batch = Batches::find($param);
            if (!$batch || $batch->Is_Deleted) {
                return response()->json(['message' => 'Batch not found'], 404);
            }
            return $batch;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Batches $batch)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($batch->Is_Deleted) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        if ($request->has('Batch_Name')) {
            $exists = Batches::where('Batch_Name', $request->Batch_Name)
                ->where('Batch_ID', '!=', $batch->Batch_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A batch with this name already exists',
                    'field' => 'Batch_Name'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Batch_Name' => 'sometimes|required|string|max:100',
            'Batch_Student_Count' => 'sometimes|required|integer',
            'Batch_Discription' => 'sometimes|required|string',
            'Status' => 'sometimes|required|in:1,0,1*'
        ]);

        $batch->update($validated);
        return $batch;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Batches::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All batches marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Batches::whereBetween('Batch_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Batches from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $batch = Batches::find($param);
            if (!$batch || $batch->Is_Deleted) {
                return response()->json(['message' => 'Batch not found'], 404);
            }
            $batch->Is_Deleted = true;
            $batch->save();
            return response()->json(['message' => 'Batch marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Batches::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All batches recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Batches::whereBetween('Batch_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Batches from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $batch = Batches::find($param);
            if (!$batch) {
                return response()->json(['message' => 'Batch not found'], 404);
            }
            if (!$batch->Is_Deleted) {
                return response()->json(['message' => 'Batch is not deleted'], 400);
            }
            $batch->Is_Deleted = false;
            $batch->save();
            return response()->json(['message' => 'Batch recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Batches::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Batches::where('Is_Deleted', true)
                    ->whereBetween('Batch_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $batch = Batches::where('Batch_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$batch) {
                return response()->json(['message' => 'Deleted batch not found'], 404);
            }
            return $batch;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
