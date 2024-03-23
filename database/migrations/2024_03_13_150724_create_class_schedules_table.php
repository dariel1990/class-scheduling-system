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
            $table->foreignId('sa_id')->references('id')->on('subject_assignments')->restrictOnDelete();
            $table->foreignId('academic_id')->references('id')->on('academic_years')->restrictOnDelete();
            $table->foreignId('room_id')->references('id')->on('rooms')->restrictOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('week_day', ['M', 'T', 'W', 'TH', 'F', 'S', 'SU']);
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
