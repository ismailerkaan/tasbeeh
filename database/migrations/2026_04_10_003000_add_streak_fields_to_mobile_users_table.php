<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobile_users', function (Blueprint $table): void {
            $table->unsignedInteger('current_streak')->default(0)->after('total_zikir_count');
            $table->unsignedInteger('best_streak')->default(0)->after('current_streak');
            $table->unsignedInteger('total_active_days')->default(0)->after('best_streak');
            $table->date('last_active_date')->nullable()->after('total_active_days');
            $table->json('daily_activity_summary')->nullable()->after('last_active_date');
        });
    }

    public function down(): void
    {
        Schema::table('mobile_users', function (Blueprint $table): void {
            $table->dropColumn([
                'current_streak',
                'best_streak',
                'total_active_days',
                'last_active_date',
                'daily_activity_summary',
            ]);
        });
    }
};
