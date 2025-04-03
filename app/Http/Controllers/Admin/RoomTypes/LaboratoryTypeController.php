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

    // ...rest of CRUD methods following same pattern as other controllers
}
