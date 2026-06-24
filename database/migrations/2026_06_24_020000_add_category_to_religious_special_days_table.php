<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('religious_special_days', function (Blueprint $table): void {
            $table->string('category')->default('mubarek_gunler_aylar')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('religious_special_days', function (Blueprint $table): void {
            $table->dropColumn('category');
        });
    }
};
