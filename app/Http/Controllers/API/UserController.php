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

    public function userLogin(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Email does not exist.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Incorrect password.'
            ], 401);
        }

        if (Auth::attempt($credentials)) {
            $token = $user->createToken('example')->accessToken;

            return response()->json([
                'status' => 200,
                'token' => $token,
                'message' => 'Login successful.'
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Login failed. Please try again.'
        ], 401);
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

        // Send registration email
        // Mail::to($user->email)->send(new RegisterMail($user));

        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }
}
