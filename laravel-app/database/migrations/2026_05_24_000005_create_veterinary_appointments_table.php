<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veterinary_appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('vet_id');
            $table->unsignedBigInteger('requested_by');
            $table->date('appointment_date');
            $table->string('reason');
            $table->string('status', 20)->default('SCHEDULED');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('pet_id')->references('pet_id')->on('pets')->cascadeOnDelete();
            $table->foreign('vet_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('requested_by')->references('user_id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veterinary_appointments');
    }
};
