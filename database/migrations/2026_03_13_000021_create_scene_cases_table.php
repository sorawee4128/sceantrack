<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scene_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->string('scene_no');
            $table->foreignId('doctor_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('assistant_user_id')->constrained('users')->restrictOnDelete();
            $table->date('case_date')->nullable();
            $table->time('notified_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->foreignId('police_station_id')->nullable()->constrained('police_stations')->nullOnDelete();
            $table->string('incident_location')->nullable();
            $table->string('deceased_name')->nullable();
            $table->foreignId('gender_id')->nullable()->constrained('genders')->nullOnDelete();
            $table->unsignedInteger('age')->nullable();
            $table->foreignId('body_handling_id')->nullable()->constrained('body_handlings')->nullOnDelete();
            $table->foreignId('notification_type_id')->nullable()->constrained('notification_types')->nullOnDelete();
            $table->date('autopsy_date')->nullable();
            $table->time('autopsy_time')->nullable();
            $table->longText('case_description')->nullable();
            $table->longText('remarks')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('autopsy_case_id')->nullable();
            

            $table->index(['case_date', 'status']);
            $table->index(['doctor_user_id', 'assistant_user_id']);
            $table->index(['scene_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scene_cases');
    }
};
