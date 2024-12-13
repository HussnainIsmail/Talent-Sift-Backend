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
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to log in the user
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
            // Catch any errors and return a failure response
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
}
