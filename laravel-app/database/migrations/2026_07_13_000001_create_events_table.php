<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('event_id', 32)->default(DB::raw("RAWTOHEX(SYS_GUID())"))->primary();
            $table->string('title', 150);
            $table->string('description', 1000);
            $table->date('event_date');
            $table->string('location', 255);
            $table->string('created_by', 32);
            $table->timestamps();

            $table->foreign('created_by')->references('user_id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
