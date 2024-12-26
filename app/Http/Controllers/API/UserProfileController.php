<?php

namespace App\Http\Controllers\API;

use App\Models\UserProfile;
use App\Http\Controllers\Controller;
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

    // Eager load the profile relationship
    $profile = $user->profile;

    return response()->json([
        'user' => $user,
        'profile' => $profile,
    ], 200);
}


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'profession' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'degrees' => 'nullable|array',
            'degrees.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // Find or create the user's profile
        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'profession' => $request->profession,
                'address' => $request->address,
                'degrees' => $request->degrees,
            ]
        );

        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile], 200);
    }
}
