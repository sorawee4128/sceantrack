<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scene_case_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scene_case_id')->constrained('scene_cases')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['scene_case_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scene_case_photos');
    }
};
