<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pet_id');
            $table->timestamp('request_date')->useCurrent();
            $table->string('status', 20)->default('PENDING');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->date('decision_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('pet_id')->references('pet_id')->on('pets')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
