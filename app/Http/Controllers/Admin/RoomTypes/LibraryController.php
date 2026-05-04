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

    // Add standard CRUD methods like index, store, show, etc
    // ...following same pattern as StudyController and RoomClassController

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
            'Room_ID' => 'required|exists:Rooms,Room_ID',
            'Lib_Number' => 'required|string|max:50',
            'Lib_Discription' => 'required|string'
        ]);

        $validated['Is_Deleted'] = false;
        $library = Library::create($validated);
        return $library->load('room');
    }

    // ... rest of CRUD methods following same pattern as StudyController
}
