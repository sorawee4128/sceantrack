<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autopsy_cases', function (Blueprint $table) {
            $table->id();
            $table->string('autopsy_no')->unique();
            $table->foreignId('doctor_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('scene_case_id')->constrained('scene_cases')->restrictOnDelete();
            $table->foreignId('police_station_id')->nullable()->constrained('police_stations')->nullOnDelete();
            $table->date('autopsy_date')->nullable();
            $table->enum('autopsy_method', ['autopsy', 'no_autopsy'])->default('autopsy');
            $table->foreignId('photo_assistant_id')->nullable()->constrained('photo_assistants')->nullOnDelete();
            $table->foreignId('autopsy_assistant_id')->nullable()->constrained('autopsy_assistants')->nullOnDelete();
            $table->foreignId('lab_id')->nullable()->constrained('labs')->nullOnDelete();
            $table->longText('remarks')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['autopsy_date', 'status']);
            $table->index(['doctor_user_id', 'scene_case_id']);
            $table->index(['photo_assistant_id', 'autopsy_assistant_id']);
            $table->index(['autopsy_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autopsy_cases');
    }
};
