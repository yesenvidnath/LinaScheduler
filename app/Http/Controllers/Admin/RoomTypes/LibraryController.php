<?php

namespace App\Http\Controllers\Admin\RoomTypes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
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

        $libraries = Library::where('Is_Deleted', false)->with('room')->get();

        if ($libraries->isEmpty()) {
            return response()->json(['message' => 'No libraries found'], 404);
        }

        return $libraries;
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Library::where('Lib_Number', $request->Lib_Number)
            ->where('Room_ID', $request->Room_ID)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A library with this number already exists in this room',
                'field' => 'Lib_Number'
            ], 422);
        }

        $validated = $request->validate([
            'Room_ID' => 'required|exists:rooms,Room_ID',
            'Lib_Number' => 'required|string|max:50',
            'Lib_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        $library = Library::create($validated);
        return $library->load('room');
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $libraries = Library::where('Is_Deleted', false)->with('room')->get();
            if ($libraries->isEmpty()) {
                return response()->json(['message' => 'No libraries found'], 404);
            }
            return $libraries;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $libraries = Library::where('Is_Deleted', false)
                    ->whereBetween('Lib_ID', [$start, $end])
                    ->with('room')
                    ->get();
                if ($libraries->isEmpty()) {
                    return response()->json(['message' => "No libraries found in range $start-$end"], 404);
                }
                return $libraries;
            }
        }

        if (is_numeric($param)) {
            $library = Library::with('room')->find($param);
            if (!$library || $library->Is_Deleted) {
                return response()->json(['message' => 'Library not found'], 404);
            }
            return $library;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Library $library)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($library->Is_Deleted) {
            return response()->json(['message' => 'Library not found'], 404);
        }

        if ($request->has('Lib_Number')) {
            $exists = Library::where('Lib_Number', $request->Lib_Number)
                ->where('Room_ID', $request->Room_ID ?? $library->Room_ID)
                ->where('Lib_ID', '!=', $library->Lib_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A library with this number already exists in this room',
                    'field' => 'Lib_Number'
                ], 422);
            }
        }

        $validated = $request->validate([
            'Room_ID' => 'sometimes|required|exists:rooms,Room_ID',
            'Lib_Number' => 'sometimes|required|string|max:50',
            'Lib_Discription' => 'sometimes|required|string'
        ]);

        $library->update($validated);
        return $library->load('room');
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Library::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All libraries marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Library::whereBetween('Lib_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Libraries from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $library = Library::find($param);
            if (!$library || $library->Is_Deleted) {
                return response()->json(['message' => 'Library not found'], 404);
            }
            $library->Is_Deleted = true;
            $library->save();
            return response()->json(['message' => 'Library marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Library::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All libraries recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Library::whereBetween('Lib_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Libraries from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $library = Library::find($param);
            if (!$library) {
                return response()->json(['message' => 'Library not found'], 404);
            }
            if (!$library->Is_Deleted) {
                return response()->json(['message' => 'Library is not deleted'], 400);
            }
            $library->Is_Deleted = false;
            $library->save();
            return response()->json(['message' => 'Library recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            $libraries = Library::where('Is_Deleted', true)->with('room')->get();
            if ($libraries->isEmpty()) {
                return response()->json(['message' => 'No deleted libraries found'], 404);
            }
            return $libraries;
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                $libraries = Library::where('Is_Deleted', true)
                    ->whereBetween('Lib_ID', [$start, $end])
                    ->with('room')
                    ->get();
                if ($libraries->isEmpty()) {
                    return response()->json(['message' => "No deleted libraries found in range $start-$end"], 404);
                }
                return $libraries;
            }
        }

        if (is_numeric($param)) {
            $library = Library::with('room')
                ->where('Lib_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$library) {
                return response()->json(['message' => 'Deleted library not found'], 404);
            }
            return $library;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
