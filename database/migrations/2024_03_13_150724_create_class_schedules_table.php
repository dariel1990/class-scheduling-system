<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->references('id')->on('faculties')->restrictOnDelete();
            $table->foreignId('sa_id')->references('id')->on('subject_assignments')->restrictOnDelete();
            $table->foreignId('academic_id')->references('id')->on('academic_years')->restrictOnDelete();
            $table->foreignId('room_id')->references('id')->on('rooms')->restrictOnDelete();
            $table->foreignId('time_slot_id')->references('id')->on('time_slots')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
