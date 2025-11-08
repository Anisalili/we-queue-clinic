<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("bookings", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->date("booking_date");
            $table->integer("queue_number");
            $table->enum("patient_category", ["bpjs", "umum"]);
            $table
                ->enum("status", [
                    "booking",
                    "menunggu",
                    "berlangsung",
                    "selesai",
                    "batal",
                ])
                ->default("booking");
            $table
                ->enum("booking_type", ["online", "walk-in"])
                ->default("online");
            $table->dateTime("check_in_time")->nullable();
            $table->dateTime("service_start_time")->nullable();
            $table->dateTime("service_end_time")->nullable();
            $table->dateTime("cancelled_at")->nullable();
            $table->string("cancellation_reason")->nullable();
            $table->text("notes")->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(["booking_date", "queue_number"]);
            $table->index("user_id");
            $table->index("status");
            $table->index("patient_category");

            // Unique constraint: one queue number per date
            $table->unique(["booking_date", "queue_number"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("bookings");
    }
};
