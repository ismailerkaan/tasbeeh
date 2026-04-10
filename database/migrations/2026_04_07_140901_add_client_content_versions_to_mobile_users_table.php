<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobile_users', function (Blueprint $table) {
            $table->unsignedInteger('zikir_version')->default(1)->after('total_zikir_count');
            $table->unsignedInteger('dua_version')->default(1)->after('zikir_version');
            $table->unsignedInteger('prayer_times_version')->default(1)->after('dua_version');
        });
    }

    public function down(): void
    {
        Schema::table('mobile_users', function (Blueprint $table) {
            $table->dropColumn([
                'zikir_version',
                'dua_version',
                'prayer_times_version',
            ]);
        });
    }
};
