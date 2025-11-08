<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[
            \Spatie\Permission\PermissionRegistrar::class
        ]->forgetCachedPermissions();

        // Define all permissions (granular, feature-based)
        $permissions = [
            // Dashboard permissions
            "view.dashboard.v1", // Patient dashboard
            "view.dashboard.v2", // Admin dashboard
            "view.dashboard.v3", // Owner dashboard

            // Booking permissions
            "booking.create",
            "booking.view.own",
            "booking.view.all",
            "booking.cancel.own",
            "booking.cancel.any",
            "booking.update",

            // Queue management permissions
            "queue.view",
            "queue.manage",
            "queue.call",

            // Patient management permissions
            "patient.register",
            "patient.view.own",
            "patient.view.all",
            "patient.update.own",
            "patient.update.any",

            // Schedule permissions
            "schedule.view",
            "schedule.configure",
            "schedule.override",

            // Report permissions
            "report.view",
            "report.export",
            "report.analytics",

            // Notification permissions
            "notification.send.manual",
            "notification.view.log",

            // User management permissions (for future admin panel)
            "user.view",
            "user.create",
            "user.update",
            "user.delete",
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(["name" => $permission]);
        }

        // Create roles and assign permissions

        // 1. Patient Role
        $patientRole = Role::create(["name" => "patient"]);
        $patientPermissions = Permission::whereIn("name", [
            "view.dashboard.v1",
            "booking.create",
            "booking.view.own",
            "booking.cancel.own",
            "patient.view.own",
            "patient.update.own",
        ])->get();
        $patientRole->syncPermissions($patientPermissions);

        // 2. Admin Role
        $adminRole = Role::create(["name" => "admin"]);
        $adminPermissions = Permission::whereIn("name", [
            "view.dashboard.v2",
            "booking.view.all",
            "booking.cancel.any",
            "booking.update",
            "queue.view",
            "queue.manage",
            "queue.call",
            "patient.register",
            "patient.view.all",
            "patient.update.any",
            "schedule.view",
            "notification.send.manual",
            "notification.view.log",
        ])->get();
        $adminRole->syncPermissions($adminPermissions);

        // 3. Owner Role (has all permissions)
        $ownerRole = Role::create(["name" => "owner"]);
        $ownerRole->syncPermissions(Permission::all());
    }
}
