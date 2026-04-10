<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobile_users', function (Blueprint $table) {
            $table->unsignedBigInteger('total_zikir_count')->default(0)->after('is_opt_in');
        });
    }

    public function down(): void
    {
        Schema::table('mobile_users', function (Blueprint $table) {
            $table->dropColumn('total_zikir_count');
        });
    }
};
