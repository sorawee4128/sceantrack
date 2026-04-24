<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scene_cases', function (Blueprint $table) {
            $table->string('scene_no')->nullable()->change();
            $table->date('case_date')->nullable()->change();
            $table->time('notified_time')->nullable()->change();
            $table->time('arrival_time')->nullable()->change();
            $table->foreignId('police_station_id')->nullable()->change();
            $table->string('incident_location')->nullable()->change();
            $table->string('deceased_name')->nullable()->change();
            $table->foreignId('gender_id')->nullable()->change();
            $table->unsignedInteger('age')->nullable()->change();
            $table->foreignId('body_handling_id')->nullable()->change();
            $table->foreignId('notification_type_id')->nullable()->change();
            $table->date('autopsy_date')->nullable()->change();
            $table->time('autopsy_time')->nullable()->change();
            $table->text('case_description')->nullable()->change();
            $table->text('remarks')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('scene_cases', function (Blueprint $table) {
            $table->string('scene_no')->nullable(false)->change();
            $table->date('case_date')->nullable(false)->change();
            $table->time('notified_time')->nullable()->change();
            $table->time('arrival_time')->nullable()->change();
            $table->foreignId('police_station_id')->nullable()->change();
            $table->string('incident_location')->nullable()->change();
            $table->string('deceased_name')->nullable()->change();
            $table->foreignId('gender_id')->nullable()->change();
            $table->unsignedInteger('age')->nullable()->change();
            $table->foreignId('body_handling_id')->nullable()->change();
            $table->foreignId('notification_type_id')->nullable()->change();
            $table->date('autopsy_date')->nullable()->change();
            $table->time('autopsy_time')->nullable()->change();
            $table->text('case_description')->nullable()->change();
            $table->text('remarks')->nullable()->change();
        });
    }
};