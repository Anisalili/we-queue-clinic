<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with("roles")->latest()->paginate(10);
        return view("users.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view("users.create", compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            "phone" => ["nullable", "string", "max:20"],
            "address" => ["nullable", "string"],
            "date_of_birth" => ["nullable", "date"],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
            "role" => ["required", "exists:roles,name"],
        ]);

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "phone" => $validated["phone"] ?? null,
            "address" => $validated["address"] ?? null,
            "date_of_birth" => $validated["date_of_birth"] ?? null,
            "password" => Hash::make($validated["password"]),
        ]);

        $user->assignRole($validated["role"]);

        return redirect()
            ->route("users.index")
            ->with("success", "User berhasil ditambahkan!");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load("roles");
        return view("users.show", compact("user"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load("roles");
        return view("users.edit", compact("user", "roles"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:users,email," . $user->id,
            ],
            "phone" => ["nullable", "string", "max:20"],
            "address" => ["nullable", "string"],
            "date_of_birth" => ["nullable", "date"],
            "password" => ["nullable", "confirmed", Rules\Password::defaults()],
            "role" => ["required", "exists:roles,name"],
        ]);

        $user->update([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "phone" => $validated["phone"] ?? null,
            "address" => $validated["address"] ?? null,
            "date_of_birth" => $validated["date_of_birth"] ?? null,
        ]);

        // Update password only if provided
        if (!empty($validated["password"])) {
            $user->update(["password" => Hash::make($validated["password"])]);
        }

        // Sync role
        $user->syncRoles([$validated["role"]]);

        return redirect()
            ->route("users.index")
            ->with("success", "User berhasil diupdate!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()
                ->route("users.index")
                ->with("error", "Tidak dapat menghapus akun sendiri!");
        }

        $user->delete();

        return redirect()
            ->route("users.index")
            ->with("success", "User berhasil dihapus!");
    }
}
