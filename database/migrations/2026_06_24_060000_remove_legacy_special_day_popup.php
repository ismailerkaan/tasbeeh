<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('religious_special_day_images');

        Schema::table('religious_special_days', function (Blueprint $table): void {
            $table->dropColumn('popup_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('religious_special_days', function (Blueprint $table): void {
            $table->boolean('popup_enabled')->default(false)->after('recommendations');
        });
    }
};
