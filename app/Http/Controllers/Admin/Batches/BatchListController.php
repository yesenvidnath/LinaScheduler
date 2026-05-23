<?php

namespace App\Http\Controllers\Admin\Batches;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Batch_List;
use Illuminate\Http\Request;

class BatchListController extends Controller
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

        return Batch_List::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'Batch_ID' => 'required|integer',
            'User_ID' => 'required|integer',
            'Branch_ID' => 'required|integer',
            'Status' => 'required|in:Active,Ended,Suspended'
        ]);

        $validated['Is_Deleted'] = false;
        return Batch_List::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Batch_List::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Batch_List::where('Is_Deleted', false)
                    ->whereBetween('Batch_List_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $batchList = Batch_List::find($param);
            if (!$batchList || $batchList->Is_Deleted) {
                return response()->json(['message' => 'Batch list record not found'], 404);
            }
            return $batchList;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Batch_List $batchList)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($batchList->Is_Deleted) {
            return response()->json(['message' => 'Batch list record not found'], 404);
        }

        $validated = $request->validate([
            'Batch_ID' => 'sometimes|required|integer',
            'User_ID' => 'sometimes|required|integer',
            'Branch_ID' => 'sometimes|required|integer',
            'Status' => 'sometimes|required|in:Active,Ended,Suspended'
        ]);

        $batchList->update($validated);
        return $batchList;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Batch_List::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All batch list records marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Batch_List::whereBetween('Batch_List_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Batch list records from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $batchList = Batch_List::find($param);
            if (!$batchList || $batchList->Is_Deleted) {
                return response()->json(['message' => 'Batch list record not found'], 404);
            }
            $batchList->Is_Deleted = true;
            $batchList->save();
            return response()->json(['message' => 'Batch list record marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Batch_List::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All batch list records recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Batch_List::whereBetween('Batch_List_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Batch list records from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $batchList = Batch_List::find($param);
            if (!$batchList) {
                return response()->json(['message' => 'Batch list record not found'], 404);
            }
            if (!$batchList->Is_Deleted) {
                return response()->json(['message' => 'Batch list record is not deleted'], 400);
            }
            $batchList->Is_Deleted = false;
            $batchList->save();
            return response()->json(['message' => 'Batch list record recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Batch_List::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Batch_List::where('Is_Deleted', true)
                    ->whereBetween('Batch_List_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $batchList = Batch_List::where('Batch_List_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$batchList) {
                return response()->json(['message' => 'Deleted batch list record not found'], 404);
            }
            return $batchList;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
