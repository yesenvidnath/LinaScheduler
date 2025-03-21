<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users; // Ensure this is correct

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'First_Name' => 'required|string|max:70',
            'Last_Name' => 'required|string|max:70',
            'email' => 'required|email|unique:users,Email',
            'password' => 'required|string|min:8|confirmed',
            'UD_ID' => 'required|integer',
            'Honorifics_ID' => 'required|integer',
            'User_Discrption' => 'nullable|string',
            'Status' => 'required|in:1,0,1*',
        ]);

        $user = Users::create([
            'First_Name' => $request->First_Name,
            'Last_Name' => $request->Last_Name,
            'Email' => $request->email, // Ensure this field is included
            'password' => Hash::make($request->password),
            'UD_ID' => $request->UD_ID,
            'Honorifics_ID' => $request->Honorifics_ID,
            'User_Discrption' => $request->User_Discrption,
            'Status' => $request->Status,
            'Is_Deleted' => false,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
