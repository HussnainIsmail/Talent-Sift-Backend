<?php

namespace App\Http\Controllers\API;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        try {
            // Fetch all permissions from the database
            $permissions = Permission::all();

            // Return a JSON response with the permissions
            return response()->json([
                'status' => 'success',
                'permissions' => $permissions,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and return a JSON error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch permissions.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
        ]);


        Permission::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'message' => 'Permission created successfully.',
        ], 201);
    }
    public function edit($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'permission' => $permission,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission->update(['name' => $request->input('name')]);

        return response()->json(['message' => 'Permission updated successfully'], 200);
    }
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
