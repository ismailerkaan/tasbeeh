<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['zikir_categories', 'dua_categories', 'hadis_categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->unsignedInteger('sort_order')->default(0)->after('name');
            });
        }
    }

    public function down(): void
    {
        foreach (['zikir_categories', 'dua_categories', 'hadis_categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('sort_order');
            });
        }
    }
};
