<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Honorific;
use App\Models\UserDesignation;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'First_Name' => 'required|string|max:70',
            'Last_Name' => 'required|string|max:70',
            'email' => 'required|email|unique:users,Email',
            'password' => 'required|string|min:8|confirmed',
            'UD_ID' => 'required|integer',
            'Honorifics_ID' => 'required|integer',
            'User_Discrption' => 'nullable|string',
            'Status' => 'required|in:1,0,1*',
        ]);

        $this->ensureDefaultLookups($validated['UD_ID'], $validated['Honorifics_ID']);

        if (!UserDesignation::where('UD_ID', $validated['UD_ID'])->exists()) {
            return response()->json([
                'message' => 'The selected user designation does not exist',
                'errors' => [
                    'UD_ID' => ['Choose an existing user designation ID. Use 1 for the default Admin designation.'],
                ],
            ], 422);
        }

        if (!Honorific::where('Honorifics_ID', $validated['Honorifics_ID'])->exists()) {
            return response()->json([
                'message' => 'The selected honorific does not exist',
                'errors' => [
                    'Honorifics_ID' => ['Choose an existing honorific ID. Use 1 for the default Mr. honorific.'],
                ],
            ], 422);
        }

        $user = Users::create([
            'First_Name' => $validated['First_Name'],
            'Last_Name' => $validated['Last_Name'],
            'Email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'UD_ID' => $validated['UD_ID'],
            'Honorifics_ID' => $validated['Honorifics_ID'],
            'User_Discrption' => $validated['User_Discrption'] ?? '',
            'Status' => $validated['Status'],
            'Is_Deleted' => false,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    private function ensureDefaultLookups(int $designationId, int $honorificId): void
    {
        if ($designationId === 1) {
            DB::table('UserDesignations')->updateOrInsert(
                ['UD_ID' => 1],
                ['Designation' => 'Admin', 'Is_Deleted' => false]
            );
        }

        if ($honorificId === 1) {
            DB::table('Honorifics')->updateOrInsert(
                ['Honorifics_ID' => 1],
                ['Honorific' => 'Mr.', 'Is_Deleted' => false]
            );
        }
    }
}
