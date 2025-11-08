<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount("permissions", "users")->get();
        return view("roles.index", compact("roles"));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $role->load("permissions");

        // Group permissions by category
        $allPermissions = Permission::all()->groupBy(function ($permission) {
            return explode(".", $permission->name)[0];
        });

        return view("roles.edit", compact("role", "allPermissions"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            "permissions" => ["nullable", "array"],
            "permissions.*" => ["exists:permissions,name"],
        ]);

        // Sync permissions
        $role->syncPermissions($validated["permissions"] ?? []);

        return redirect()
            ->route("roles.index")
            ->with(
                "success",
                'Role "' . ucfirst($role->name) . '" berhasil diupdate!',
            );
    }

    /**
     * Show role details.
     */
    public function show(Role $role)
    {
        $role->loadCount("users");
        $role->load("permissions");

        // Group permissions by category
        $permissions = $role->permissions->groupBy(function ($permission) {
            return explode(".", $permission->name)[0];
        });

        return view("roles.show", compact("role", "permissions"));
    }
}
