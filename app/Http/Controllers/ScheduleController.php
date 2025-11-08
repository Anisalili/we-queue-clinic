<?php

namespace App\Http\Controllers;

use App\Models\ScheduleDefault;
use App\Models\ScheduleOverride;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // Default Schedule Management
    public function index()
    {
        $schedules = ScheduleDefault::orderedByDay()->get();
        $upcomingOverrides = ScheduleOverride::upcoming()->limit(10)->get();
        $upcomingHolidays = Holiday::upcoming()->limit(5)->get();

        return view(
            "schedules.index",
            compact("schedules", "upcomingOverrides", "upcomingHolidays"),
        );
    }

    public function updateDefault(Request $request, ScheduleDefault $schedule)
    {
        $validated = $request->validate([
            "start_time" => "nullable|date_format:H:i",
            "end_time" => "nullable|date_format:H:i|after:start_time",
            "max_slots" => "required|integer|min:1|max:100",
            "is_active" => "boolean",
        ]);

        $schedule->update($validated);

        return redirect()
            ->route("schedules.index")
            ->with(
                "success",
                "Jadwal " . $schedule->day_name . " berhasil diupdate!",
            );
    }

    // Override Management
    public function overrides()
    {
        $overrides = ScheduleOverride::orderBy("date", "desc")->paginate(15);
        return view("schedules.overrides", compact("overrides"));
    }

    public function createOverride()
    {
        return view("schedules.create-override");
    }

    public function storeOverride(Request $request)
    {
        $validated = $request->validate([
            "date" =>
                "required|date|after_or_equal:today|unique:schedule_overrides,date",
            "start_time" => "nullable|date_format:H:i",
            "end_time" => "nullable|date_format:H:i|after:start_time",
            "max_slots" => "nullable|integer|min:1|max:100",
            "is_closed" => "boolean",
            "reason" => "required|string|max:500",
        ]);

        ScheduleOverride::create($validated);

        return redirect()
            ->route("schedules.overrides")
            ->with("success", "Override jadwal berhasil ditambahkan!");
    }

    public function editOverride(ScheduleOverride $override)
    {
        return view("schedules.edit-override", compact("override"));
    }

    public function updateOverride(Request $request, ScheduleOverride $override)
    {
        $validated = $request->validate([
            "date" =>
                "required|date|unique:schedule_overrides,date," . $override->id,
            "start_time" => "nullable|date_format:H:i",
            "end_time" => "nullable|date_format:H:i|after:start_time",
            "max_slots" => "nullable|integer|min:1|max:100",
            "is_closed" => "boolean",
            "reason" => "required|string|max:500",
        ]);

        $override->update($validated);

        return redirect()
            ->route("schedules.overrides")
            ->with("success", "Override jadwal berhasil diupdate!");
    }

    public function destroyOverride(ScheduleOverride $override)
    {
        $override->delete();

        return redirect()
            ->route("schedules.overrides")
            ->with("success", "Override jadwal berhasil dihapus!");
    }

    // Holiday Management
    public function holidays()
    {
        $holidays = Holiday::orderBy("date", "desc")->paginate(15);
        return view("schedules.holidays", compact("holidays"));
    }

    public function createHoliday()
    {
        return view("schedules.create-holiday");
    }

    public function storeHoliday(Request $request)
    {
        $validated = $request->validate([
            "date" => "required|date",
            "name" => "required|string|max:255",
            "type" => "required|in:national,clinic_leave,emergency",
            "description" => "nullable|string|max:500",
        ]);

        Holiday::create($validated);

        return redirect()
            ->route("schedules.holidays")
            ->with("success", "Hari libur berhasil ditambahkan!");
    }

    public function destroyHoliday(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()
            ->route("schedules.holidays")
            ->with("success", "Hari libur berhasil dihapus!");
    }
}
