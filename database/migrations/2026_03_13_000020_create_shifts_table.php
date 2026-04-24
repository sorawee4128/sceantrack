<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->date('shift_date');
            $table->enum('shift_type', ['day', 'night']);
            $table->foreignId('doctor_user_id')->constrained('users');
            $table->foreignId('assistant_user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['shift_date', 'shift_type']);
            $table->index(['shift_date', 'doctor_user_id']);
            $table->index(['shift_date', 'assistant_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
