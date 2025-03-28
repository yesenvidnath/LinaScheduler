<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UserDesignation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminVerificationController extends Controller
{
    public function isAdmin()
    {
        $userId = $this->getAuthenticatedUserId();
        $user = Users::find($userId);

        if (!$user) {
            return false;
        }

        $userDesignation = UserDesignation::find($user->UD_ID);

        if (!$userDesignation) {
            return false;
        }

        // Case-insensitive check for 'admin' in the designation
        return Str::contains(Str::lower($userDesignation->Designation), 'admin');
    }

    protected function getAuthenticatedUserId()
    {
        $user = Auth::user();
        return $user ? $user->User_ID : null;
    }
}
