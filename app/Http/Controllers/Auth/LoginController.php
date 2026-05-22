<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Users::where('email', $request->email)->first();

        if ($user && $user->login_attempts >= 3) {
            return response()->json(['message' => 'Account locked due to too many login attempts.'], 423);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user->login_attempts = 0;
            $user->save();
            $user->load('userDesignation', 'honorifics');

            $designation = optional($user->userDesignation)->Designation;
            $isAdmin = $designation ? Str::contains(Str::lower($designation), 'admin') : false;
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
                'role' => $designation,
                'is_admin' => $isAdmin,
            ], 200);
        } else {
            if ($user) {
                $user->increment('login_attempts');
            }
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function recoverPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'current_password' => 'required|string',
            'secret_passcode' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 401);
        }

        // The passcode is the current day of the week, the current year, and the current month, please make sure to adjust the format as needed and keep it consistent with the expected format.
        $currentDate = Carbon::now();
        $expectedPasscode = $currentDate->format('l') . $currentDate->year . $currentDate->format('F');

        if ($request->secret_passcode !== $expectedPasscode) {
            return response()->json(['message' => 'Invalid secret passcode'], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }

    /**
     * Helper method to retrieve the authenticated user's ID.
     */
    protected function getAuthenticatedUserId()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated and return the user_ID
        return $user ? $user->User_ID : null;
    }
}
