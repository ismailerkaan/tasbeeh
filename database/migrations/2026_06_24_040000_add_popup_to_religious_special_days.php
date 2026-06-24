<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('religious_special_days', function (Blueprint $table): void {
            $table->boolean('popup_enabled')->default(false)->after('recommendations');
        });

        Schema::create('religious_special_day_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('religious_special_day_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('religious_special_day_images');

        Schema::table('religious_special_days', function (Blueprint $table): void {
            $table->dropColumn('popup_enabled');
        });
    }
};
