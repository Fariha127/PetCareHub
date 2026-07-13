<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->string('request_id', 32)->default(DB::raw("RAWTOHEX(SYS_GUID())"))->primary();
            $table->string('user_id', 32);
            $table->string('pet_id', 32);
            $table->timestamp('request_date')->useCurrent();
            $table->string('status', 20)->default('PENDING');
            $table->string('reviewed_by', 32)->nullable();
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
