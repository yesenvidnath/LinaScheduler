<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Verify\AdminVerificationController;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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

        return Users::where('Is_Deleted', false)->get();
    }

    public function store(Request $request)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $exists = Users::where('Email', $request->Email)
            ->where('Is_Deleted', false)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A user with this email already exists',
                'field' => 'Email'
            ], 422);
        }

        $validated = $request->validate([
            'UD_ID' => 'required|integer',
            'Honorifics_ID' => 'required|integer',
            'First_Name' => 'required|string|max:70',
            'Last_Name' => 'required|string|max:70',
            'Email' => 'required|email',
            'User_Discrption' => 'required|string',
            'Status' => 'required|in:1,0,1*',
            'password' => 'required|string'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['Is_Deleted'] = false;
        return Users::create($validated);
    }

    public function show($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Users::where('Is_Deleted', false)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Users::where('Is_Deleted', false)
                    ->whereBetween('User_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $user = Users::find($param);
            if (!$user || $user->Is_Deleted) {
                return response()->json(['message' => 'User not found'], 404);
            }
            return $user;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function update(Request $request, Users $user)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($user->Is_Deleted) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($request->has('Email')) {
            $exists = Users::where('Email', $request->Email)
                ->where('User_ID', '!=', $user->User_ID)
                ->where('Is_Deleted', false)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A user with this email already exists',
                    'field' => 'Email'
                ], 422);
            }
        }

        $validated = $request->validate([
            'UD_ID' => 'sometimes|required|integer',
            'Honorifics_ID' => 'sometimes|required|integer',
            'First_Name' => 'sometimes|required|string|max:70',
            'Last_Name' => 'sometimes|required|string|max:70',
            'Email' => 'sometimes|required|email',
            'User_Discrption' => 'sometimes|required|string',
            'Status' => 'sometimes|required|in:1,0,1*',
            'password' => 'sometimes|required|string'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return $user;
    }

    public function destroy($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Users::where('Is_Deleted', false)->update(['Is_Deleted' => true]);
            return response()->json(['message' => 'All users marked as deleted successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Users::whereBetween('User_ID', [$start, $end])
                    ->where('Is_Deleted', false)
                    ->update(['Is_Deleted' => true]);
                return response()->json(['message' => "Users from $start to $end marked as deleted successfully"]);
            }
        }

        if (is_numeric($param)) {
            $user = Users::find($param);
            if (!$user || $user->Is_Deleted) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->Is_Deleted = true;
            $user->save();
            return response()->json(['message' => 'User marked as deleted successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function recover($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            Users::where('Is_Deleted', true)->update(['Is_Deleted' => false]);
            return response()->json(['message' => 'All users recovered successfully']);
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                Users::whereBetween('User_ID', [$start, $end])
                    ->where('Is_Deleted', true)
                    ->update(['Is_Deleted' => false]);
                return response()->json(['message' => "Users from $start to $end recovered successfully"]);
            }
        }

        if (is_numeric($param)) {
            $user = Users::find($param);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            if (!$user->Is_Deleted) {
                return response()->json(['message' => 'User is not deleted'], 400);
            }
            $user->Is_Deleted = false;
            $user->save();
            return response()->json(['message' => 'User recovered successfully']);
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }

    public function showDeleted($param)
    {
        if (!$this->adminVerifier->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($param === '*') {
            return Users::where('Is_Deleted', true)->get();
        }

        if (strpos($param, '-') !== false) {
            list($start, $end) = explode('-', $param);
            if (is_numeric($start) && is_numeric($end)) {
                return Users::where('Is_Deleted', true)
                    ->whereBetween('User_ID', [$start, $end])
                    ->get();
            }
        }

        if (is_numeric($param)) {
            $user = Users::where('User_ID', $param)
                ->where('Is_Deleted', true)
                ->first();
            if (!$user) {
                return response()->json(['message' => 'Deleted user not found'], 404);
            }
            return $user;
        }

        return response()->json(['message' => 'Invalid parameter format'], 400);
    }
}
