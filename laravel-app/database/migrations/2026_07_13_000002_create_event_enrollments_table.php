<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_enrollments', function (Blueprint $table) {
            $table->string('enrollment_id', 32)->default(DB::raw("RAWTOHEX(SYS_GUID())"))->primary();
            $table->string('event_id', 32);
            $table->string('user_id', 32);
            $table->string('status', 20); // INTERESTED, GOING
            $table->timestamps();

            $table->foreign('event_id')->references('event_id')->on('events')->cascadeOnDelete();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_enrollments');
    }
};
