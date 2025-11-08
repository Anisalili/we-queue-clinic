<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions (read-only).
     */
    public function index()
    {
        // Group permissions by category
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(".", $permission->name)[0];
        });

        $totalPermissions = Permission::count();

        return view(
            "permissions.index",
            compact("permissions", "totalPermissions"),
        );
    }
}
