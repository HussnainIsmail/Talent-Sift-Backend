<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\RegisterMail;
use Mail;

class UserController extends Controller
{
    public function index()
    {
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
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('YourApp')->accessToken;

            // Store the token in the api_token column
            $user->api_token = $token;
            $user->save();

            return response()->json([
                'token' => $token,
                'message' => 'Login successful'
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function register(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);

            // Return the user details (including role) for the edit page
            return response()->json([
                'user' => $user
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



        try {
            $user = User::findOrFail($id);

            // Update user details
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
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

    public function userLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
