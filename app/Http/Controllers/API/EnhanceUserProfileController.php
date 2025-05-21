<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class EnhanceUserProfileController extends Controller
{
    public function enhanceProfile(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'enhance_profile_profession' => 'required|string|max:255',
            'enhance_profile_skills' => 'required|string',
            'enhance_profile_experience' => 'required|string',
        ]);

        // Find existing UserProfile or create new instance
        $userProfile = UserProfile::firstOrNew(['user_id' => $request->id]);

        $userProfile->enhance_profile_profession = $request->enhance_profile_profession;

        // Convert comma separated string into JSON array string
        $skillsArray = array_map('trim', explode(',', $request->enhance_profile_skills));
        $userProfile->enhance_profile_skills = json_encode($skillsArray);

        $userProfile->enhance_profile_experience = $request->enhance_profile_experience;

        $userProfile->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $userProfile,
        ]);
    }


public function getEnhancedProfile(Request $request)
{
    $user = $request->user(); // authenticated user
    
    $userProfile = UserProfile::where('user_id', $user->id)->first();

    if (!$userProfile) {
        return response()->json([
            'profile' => null,
        ]);
    }

    // Decode the skills JSON string to array or empty array if null
    $skills = $userProfile->enhance_profile_skills ? json_decode($userProfile->enhance_profile_skills) : [];

    return response()->json([
        'profile' => [
            'enhance_profile_profession' => $userProfile->enhance_profile_profession,
            'enhance_profile_skills' => implode(', ', $skills), // convert array back to comma separated string
            'enhance_profile_experience' => $userProfile->enhance_profile_experience,
        ],
    ]);
}

}
