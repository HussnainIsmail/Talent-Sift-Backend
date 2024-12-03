<?php

namespace App\Http\Controllers\API;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        // Return the roles along with their permissions
        return response()->json([
            'roles' => $roles->map(function ($role) {
                // Return only the role name and its associated permissions as names
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'array', // Validate permissions array
            'permissions.*' => 'exists:permissions,id', // Ensure each permission ID exists
        ]);

        // Create the role using Spatie's Role model
        $role = Role::create(['name' => $request->input('name')]);

        // Assign permissions to the role
        if ($request->has('permissions') && count($request->input('permissions')) > 0) {
            foreach ($request->input('permissions') as $permissionId) {
                // Use Spatie's `givePermissionTo` method
                $permission = Permission::findById($permissionId);
                $role->givePermissionTo($permission);
            }
        }

        return response()->json([
            'message' => 'Role created successfully.',
            'role' => $role,
        ], 201);
    }



    public function edit($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Fetch all permissions from the role_and_permission table
        $permissions = $role->permissions;  // Assuming a many-to-many relationship

        // Get the permission ids for the role
        $rolePermissions = $permissions->pluck('id')->toArray();

        // Get all available permissions
        $allPermissions = Permission::all();

        return response()->json([
            'status' => 'success',
            'role' => $role,
            'permissions' => $allPermissions,
            'role_permissions' => $rolePermissions,  // Permissions assigned to this role
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id', 
        ]);

        // Find the role by ID
        $role = Role::findOrFail($id);

        // Update the role name
        $role->name = $request->name;
        $role->save();

        // Sync the permissions for the role
        $role->permissions()->sync($request->permissions); // Update the pivot table

        // Return success message
        return response()->json(['message' => 'Role updated successfully.']);
    }
    public function addPermissionToRoles($roleId)
    {
        try {
            $permissions = Permission::get();
            $roles = Role::findOrFail($roleId);

            return response()->json([
                'status' => 'success',
                'roles' => $roles,
                'permissions' => $permissions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch roles and permissions.',
            ], 500);
        }
    }

    public function assignPermissions(Request $request, Role $role)
    {
        // Validate the request
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name', // Ensure the permissions exist
        ]);

        // Assign permissions to the role
        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'role' => $role->load('permissions'),
        ]);
    }
}
