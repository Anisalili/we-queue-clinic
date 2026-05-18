<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lean permission set — only permissions actually gated in routes/views.
        $permissions = [
            // Dashboards
            "view.dashboard.v1",     // patient
            "view.dashboard.v2",     // admin
            "view.dashboard.v3",     // owner

            // Booking
            "booking.create",        // patient creates own booking
            "booking.view.own",      // patient sees own bookings
            "booking.view.all",      // staff sees all bookings
            "booking.cancel.own",    // patient cancels own
            "booking.cancel.any",    // staff cancels any
            "booking.update",        // staff check-in / edits

            // Queue
            "queue.view",
            "queue.manage",

            // Patient (walk-in registration)
            "patient.register",

            // Schedule (owner-only configuration)
            "schedule.configure",

            // Reports
            "report.view",
            "report.export",

            // User management
            "user.view",
            "user.create",
            "user.update",
            "user.delete",
        ];

        foreach ($permissions as $permission) {
            Permission::create(["name" => $permission]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ---- Patient: book + manage own only ----
        $patient = Role::create(["name" => "patient"]);
        $patient->syncPermissions([
            "view.dashboard.v1",
            "booking.create",
            "booking.view.own",
            "booking.cancel.own",
        ]);

        // Shared staff perms (both admin & owner/dokter).
        $sharedStaffPerms = [
            "booking.view.all",
            "booking.cancel.any",
            "booking.update",
            "queue.view",
            "queue.manage",
            "report.view",
            "report.export",
            "user.view",
            "user.create",
            "user.update",
            "user.delete",
        ];

        // ---- Admin: shared staff perms + schedule mgmt + walk-in registration ----
        $admin = Role::create(["name" => "admin"]);
        $admin->syncPermissions(array_merge($sharedStaffPerms, [
            "view.dashboard.v2",
            "schedule.configure",
            "patient.register",
        ]));

        // ---- Dokter: same as admin EXCEPT schedule mgmt & walk-in ----
        $dokter = Role::create(["name" => "dokter"]);
        $dokter->syncPermissions(array_merge($sharedStaffPerms, [
            "view.dashboard.v3",
        ]));
    }
}
