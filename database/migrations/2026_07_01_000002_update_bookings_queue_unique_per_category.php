<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("bookings", function (Blueprint $table) {
            // BPJS and Umum now have independent queue numbering, so the
            // uniqueness must include the patient category.
            $table->dropUnique(["booking_date", "queue_number"]);
            $table->unique([
                "booking_date",
                "patient_category",
                "queue_number",
            ]);
        });
    }

    public function down(): void
    {
        Schema::table("bookings", function (Blueprint $table) {
            $table->dropUnique([
                "booking_date",
                "patient_category",
                "queue_number",
            ]);
            $table->unique(["booking_date", "queue_number"]);
        });
    }
};
