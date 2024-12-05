<?php

namespace App\Http\Controllers\API;
use Spatie\Permission\Models\Role;
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
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
    
        // Attempt to authenticate the user using Auth::attempt()
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication successful
            $user = Auth::user();
            $roleName = $user->role; 
            $role = \DB::table('roles')
                ->where('name', $roleName)
                ->first(); 
    
            $permissions = \DB::table('role_has_permissions')
                ->where('role_id', $role->id)
                ->pluck('permission_id');
    
            $permissionNames = \DB::table('permissions')
                ->whereIn('id', $permissions)
                ->pluck('name');
    
            // Create the token
            $token = $user->createToken('YourAppName')->accessToken;
    
            return response()->json([
                'token' => $token, 
                'role' => $roleName,
                'permissions' => $permissionNames, 
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
