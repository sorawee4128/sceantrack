<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('full_name')->nullable()->after('email');
            $table->string('phone')->nullable()->after('full_name');
            $table->string('position')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'full_name',
                'phone',
                'position',
                'is_active',
            ]);
        });
    }
};