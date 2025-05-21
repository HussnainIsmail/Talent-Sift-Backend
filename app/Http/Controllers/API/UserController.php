<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


use App\Mail\RegisterMail;


class UserController extends Controller
{



    public function companylist()
    {
        try {
            // Fetch all companies (you can select only 'name' if needed)
            $companies = Company::all();
            return response()->json([
                'data' => $companies,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Unexpected Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }


    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        try {
            $users = User::all();

            return response()->json([
                'users' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch users.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $role = $user->role;
            $roleData = \DB::table('roles')
                ->where('name', $role)
                ->first();
            if (!$roleData) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            // Get the permissions associated with the role
            $permissions = \DB::table('role_has_permissions')
                ->where('role_id', $roleData->id)
                ->pluck('permission_id');

            // Fetch the permission names from the 'permissions' table
            $permissionNames = \DB::table('permissions')
                ->whereIn('id', $permissions)
                ->pluck('name');

            // Create the token
            $token = $user->createToken('YourAppName')->accessToken;

            // Store the token in the users table
            $user->api_token = $token;
            $user->save();
            return response()->json([
                'token' => $token,
                'role' => $role,
                'permissions' => $permissionNames,
                'name' => $user->name,
                'message' => 'Login successful'
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function register(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'user_type' => 'required|in:candidate,recuriter',
            'company_name' => 'required_if:user_type,recuriter|nullable|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_foundation_date' => 'nullable|date',
            'services' => 'nullable|array',
            'company_location' => 'nullable|string|max:255',
        ]);

        // If validation fails, return errors in JSON
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->user_type, // Store either 'candidate' or 'recuriter'
        ]);

        // If recruiter, save company data
        if ($request->user_type === 'recuriter') {
            Company::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'contact_no' => $request->contact_no ?? null,
                'company_email' => $request->company_email ?? null,
                'company_foundation_date' => $request->company_foundation_date ?? null,
                'services' => $request->services ? json_encode($request->services) : null,
                'company_location' => $request->company_location ?? null,
            ]);
        }

        // Mail::to($user->email)->send(new RegisterMail($user));

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user
        ], 201);
    }



    public function edit($id)
    {
        try {
            $user = auth()->user();
            $userToEdit = User::findOrFail($id);
            return response()->json([
                'user' => $userToEdit
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string|max:255',
        ]);

        try {

            $user = User::findOrFail($id);
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];
            $user->save();

            return response()->json([
                'message' => 'User updated successfully.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'user deleted successfully']);
    }
    public function userLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate OTP
        $otp = Str::random(6);  // Generate a 6-digit OTP

        // Retrieve user by email
        $user = User::where('email', $request->email)->first();

        // Store OTP in the user's record
        $user->otp = $otp;
        $user->save();

        // Send OTP via Mailtrap email
        Mail::to($user->email)->send(new ForgotPasswordMail($user, $otp));

        return response()->json(['message' => 'OTP sent successfully.'], 200);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Optionally, clear OTP after verification
        $user->otp = null;
        $user->save();

        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successful']);
    }
}
