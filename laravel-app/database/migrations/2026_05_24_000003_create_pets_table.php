<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->string('pet_id', 32)->default(DB::raw("RAWTOHEX(SYS_GUID())"))->primary();
            $table->string('pet_name');
            $table->string('species', 60);
            $table->string('breed', 80)->nullable();
            $table->unsignedSmallInteger('age');
            $table->string('gender', 10);
            $table->string('vaccination_status', 30);
            $table->string('health_condition')->nullable();
            $table->string('adoption_status', 20)->default('AVAILABLE');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
