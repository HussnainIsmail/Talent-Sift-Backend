<?php

namespace App\Http\Controllers\API;

use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 401);
            }

        $profile = $user->profile;

        return response()->json([
            'user' => $user,
            'profile' => $profile,
        ], 200);
    }

   
}
