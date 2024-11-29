<?php

namespace App\Http\Controllers\API;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        try {
            // Fetch all permissions from the database
            $roles = Role::all();

            // Return a JSON response with the permissions
            return response()->json([
                'status' => 'success',
                'roles' => $roles,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and return a JSON error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch roles.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
        ]);


        Role::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'message' => 'Role created successfully.',
        ], 201);
    }
    public function edit($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'role' => $role,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role->update(['name' => $request->input('name')]);

        return response()->json(['message' => 'Role updated successfully'], 200);
    }
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
