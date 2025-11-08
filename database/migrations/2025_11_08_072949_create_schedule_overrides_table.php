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
        Schema::create("schedule_overrides", function (Blueprint $table) {
            $table->id();
            $table->date("date")->unique();
            $table->time("start_time")->nullable();
            $table->time("end_time")->nullable();
            $table->integer("max_slots")->nullable();
            $table->boolean("is_closed")->default(false);
            $table->text("reason")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("schedule_overrides");
    }
};
