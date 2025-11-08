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
            // Admin/Owner - View all bookings
            Route::get("/", [
                \App\Http\Controllers\BookingController::class,
                "index",
            ])
                ->middleware("permission:booking.view.all")
                ->name("index");

            // Patient - Create booking
            Route::get("/create", [
                \App\Http\Controllers\BookingController::class,
                "create",
            ])
                ->middleware("permission:booking.create")
                ->name("create");

            Route::post("/", [
                \App\Http\Controllers\BookingController::class,
                "store",
            ])
                ->middleware("permission:booking.create")
                ->name("store");

            // Patient - My bookings
            Route::get("/mine", [
                \App\Http\Controllers\BookingController::class,
                "mine",
            ])
                ->middleware("permission:booking.view.own")
                ->name("mine");

            // View specific booking
            Route::get("/{booking}", [
                \App\Http\Controllers\BookingController::class,
                "show",
            ])->name("show");

            // Cancel booking
            Route::post("/{booking}/cancel", [
                \App\Http\Controllers\BookingController::class,
                "cancel",
            ])->name("cancel");

            // Admin/Owner actions
            Route::post("/{booking}/check-in", [
                \App\Http\Controllers\BookingController::class,
                "checkIn",
            ])
                ->middleware("permission:booking.update")
                ->name("check-in");

            Route::post("/{booking}/start-service", [
                \App\Http\Controllers\BookingController::class,
                "startService",
            ])
                ->middleware("permission:queue.manage")
                ->name("start-service");

            Route::post("/{booking}/finish-service", [
                \App\Http\Controllers\BookingController::class,
                "finishService",
            ])
                ->middleware("permission:queue.manage")
                ->name("finish-service");

            // AJAX endpoint for checking slots
            Route::post("/check-slots", [
                \App\Http\Controllers\BookingController::class,
                "checkSlots",
            ])->name("check-slots");
        });

    // Queue routes (permission-based)
    Route::prefix("queue")
        ->name("queue.")
        ->group(function () {
            // Queue dashboard (Admin/Owner)
            Route::get("/", [
                \App\Http\Controllers\QueueController::class,
                "index",
            ])
                ->middleware("permission:queue.view")
                ->name("index");

            // Public queue display (TV screen)
            Route::get("/display", [
                \App\Http\Controllers\QueueController::class,
                "display",
            ])->name("display");

            // Queue actions
            Route::post("/call-next", [
                \App\Http\Controllers\QueueController::class,
                "callNext",
            ])
                ->middleware("permission:queue.manage")
                ->name("call-next");

            Route::post("/{booking}/call", [
                \App\Http\Controllers\QueueController::class,
                "callSpecific",
            ])
                ->middleware("permission:queue.manage")
                ->name("call-specific");

            Route::post("/{booking}/skip", [
                \App\Http\Controllers\QueueController::class,
                "skip",
            ])
                ->middleware("permission:queue.manage")
                ->name("skip");

            // AJAX endpoint for real-time updates
            Route::get("/data", [
                \App\Http\Controllers\QueueController::class,
                "getData",
            ])
                ->middleware("permission:queue.view")
                ->name("data");
        });

    // Patient routes (permission-based)
    Route::prefix("patient")
        ->name("patient.")
        ->group(function () {
            // Walk-in registration (Admin only)
            Route::get("/register", [
                \App\Http\Controllers\PatientController::class,
                "register",
            ])
                ->middleware("permission:patient.register")
                ->name("register");

            Route::post("/store-walkin", [
                \App\Http\Controllers\PatientController::class,
                "storeWalkin",
            ])
                ->middleware("permission:patient.register")
                ->name("store-walkin");
        });

    // API routes for patient search
    Route::get("/api/patients/search", [
        \App\Http\Controllers\PatientController::class,
        "search",
    ])->middleware("auth");

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
        ->middleware("permission:report.view")
        ->group(function () {
            Route::get("/", [
                \App\Http\Controllers\ReportController::class,
                "index",
            ])->name("index");

            // AJAX endpoints
            Route::get("/summary", [
                \App\Http\Controllers\ReportController::class,
                "summary",
            ])->name("summary");

            Route::get("/detailed", [
                \App\Http\Controllers\ReportController::class,
                "detailed",
            ])->name("detailed");

            Route::get("/performance", [
                \App\Http\Controllers\ReportController::class,
                "performance",
            ])->name("performance");

            // Export routes
            Route::get("/export-excel", [
                \App\Http\Controllers\ReportController::class,
                "exportExcel",
            ])
                ->middleware("permission:report.export")
                ->name("export.excel");

            Route::get("/export-pdf", [
                \App\Http\Controllers\ReportController::class,
                "exportPdf",
            ])
                ->middleware("permission:report.export")
                ->name("export.pdf");
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
