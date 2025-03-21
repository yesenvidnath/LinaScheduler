<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Users;

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
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            if ($user) {
                $user->increment('login_attempts');
            }
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function recoverPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent.'], 200)
            : response()->json(['message' => 'Unable to send password reset link.'], 500);
    }
}
 