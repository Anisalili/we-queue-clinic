<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardV1Controller;
use App\Http\Controllers\Dashboard\DashboardV2Controller;
use App\Http\Controllers\Dashboard\DashboardV3Controller;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

// Redirect /dashboard based on user permissions
Route::get("/dashboard", function () {
    $user = auth()->user();

    if ($user->can("view.dashboard.v3")) {
        return redirect()->route("dashboard.v3");
    } elseif ($user->can("view.dashboard.v2")) {
        return redirect()->route("dashboard.v2");
    } elseif ($user->can("view.dashboard.v1")) {
        return redirect()->route("dashboard.v1");
    }

    abort(403, "You do not have permission to access any dashboard.");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    // Profile routes
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    // Dashboard routes (permission-based)
    Route::get("/dashboard/v1", [DashboardV1Controller::class, "index"])
        ->middleware("permission:view.dashboard.v1")
        ->name("dashboard.v1");

    Route::get("/dashboard/v2", [DashboardV2Controller::class, "index"])
        ->middleware("permission:view.dashboard.v2")
        ->name("dashboard.v2");

    Route::get("/dashboard/v3", [DashboardV3Controller::class, "index"])
        ->middleware("permission:view.dashboard.v3")
        ->name("dashboard.v3");

    // Booking routes (permission-based)
    Route::prefix("booking")
        ->name("booking.")
        ->group(function () {
            Route::get("/", function () {
                return view("booking.index");
            })
                ->middleware("permission:booking.view.all")
                ->name("index");

            Route::get("/create", function () {
                return view("booking.create");
            })
                ->middleware("permission:booking.create")
                ->name("create");

            Route::get("/mine", function () {
                return view("booking.mine");
            })
                ->middleware("permission:booking.view.own")
                ->name("mine");
        });

    // Queue routes (permission-based)
    Route::prefix("queue")
        ->name("queue.")
        ->group(function () {
            Route::get("/", function () {
                return view("queue.index");
            })
                ->middleware("permission:queue.view")
                ->name("index");
        });

    // Patient routes (permission-based)
    Route::prefix("patient")
        ->name("patient.")
        ->group(function () {
            Route::get("/register", function () {
                return view("patient.register");
            })
                ->middleware("permission:patient.register")
                ->name("register");
        });

    // Schedule routes (permission-based)
    Route::prefix("schedules")
        ->name("schedules.")
        ->middleware("permission:schedule.configure")
        ->group(function () {
            // Main schedule index
            Route::get("/", [
                \App\Http\Controllers\ScheduleController::class,
                "index",
            ])->name("index");

            // Default schedule update
            Route::patch("/default/{schedule}", [
                \App\Http\Controllers\ScheduleController::class,
                "updateDefault",
            ])->name("update-default");

            // Schedule Overrides
            Route::get("/overrides", [
                \App\Http\Controllers\ScheduleController::class,
                "overrides",
            ])->name("overrides");
            Route::get("/overrides/create", [
                \App\Http\Controllers\ScheduleController::class,
                "createOverride",
            ])->name("overrides.create");
            Route::post("/overrides", [
                \App\Http\Controllers\ScheduleController::class,
                "storeOverride",
            ])->name("overrides.store");
            Route::get("/overrides/{override}/edit", [
                \App\Http\Controllers\ScheduleController::class,
                "editOverride",
            ])->name("overrides.edit");
            Route::patch("/overrides/{override}", [
                \App\Http\Controllers\ScheduleController::class,
                "updateOverride",
            ])->name("overrides.update");
            Route::delete("/overrides/{override}", [
                \App\Http\Controllers\ScheduleController::class,
                "destroyOverride",
            ])->name("overrides.destroy");

            // Holidays
            Route::get("/holidays", [
                \App\Http\Controllers\ScheduleController::class,
                "holidays",
            ])->name("holidays");
            Route::get("/holidays/create", [
                \App\Http\Controllers\ScheduleController::class,
                "createHoliday",
            ])->name("holidays.create");
            Route::post("/holidays", [
                \App\Http\Controllers\ScheduleController::class,
                "storeHoliday",
            ])->name("holidays.store");
            Route::delete("/holidays/{holiday}", [
                \App\Http\Controllers\ScheduleController::class,
                "destroyHoliday",
            ])->name("holidays.destroy");
        });

    // Report routes (permission-based)
    Route::prefix("report")
        ->name("report.")
        ->group(function () {
            Route::get("/", function () {
                return view("report.index");
            })
                ->middleware("permission:report.view")
                ->name("index");
        });

    // User Management routes (permission-based)
    Route::resource(
        "users",
        \App\Http\Controllers\UserManagementController::class,
    )->middleware("permission:user.view");

    // Role Management routes (permission-based)
    Route::resource(
        "roles",
        \App\Http\Controllers\RoleManagementController::class,
    )
        ->only(["index", "show", "edit", "update"])
        ->middleware("permission:user.view");

    // Permission List (read-only)
    Route::get("permissions", [
        \App\Http\Controllers\PermissionController::class,
        "index",
    ])
        ->name("permissions.index")
        ->middleware("permission:user.view");
});

require __DIR__ . "/auth.php";
