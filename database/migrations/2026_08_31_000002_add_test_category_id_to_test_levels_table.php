<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_levels', function (Blueprint $table): void {
            $table->foreignId('test_category_id')->nullable()->after('id');
            $table->index(['test_category_id', 'is_active', 'sort_order'], 'tl_cat_active_sort_idx');
            $table->foreign('test_category_id', 'tl_category_fk')
                ->references('id')
                ->on('test_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('test_levels', function (Blueprint $table): void {
            $table->dropForeign('tl_category_fk');
            $table->dropIndex('tl_cat_active_sort_idx');
            $table->dropColumn('test_category_id');
        });
    }
};