<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->string('record_id', 32)->default(DB::raw("RAWTOHEX(SYS_GUID())"))->primary();
            $table->string('pet_id', 32);
            $table->string('vet_id', 32);
            $table->string('diagnosis');
            $table->string('treatment');
            $table->date('vaccination_date')->nullable();
            $table->date('next_vaccine_date')->nullable();
            $table->string('prescription')->nullable();
            $table->timestamps();

            $table->foreign('pet_id')->references('pet_id')->on('pets')->cascadeOnDelete();
            $table->foreign('vet_id')->references('user_id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
