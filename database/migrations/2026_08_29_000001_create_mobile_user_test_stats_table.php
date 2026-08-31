<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_user_test_stats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mobile_user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('total_score')->default(0);
            $table->unsignedInteger('best_run_score')->default(0);
            $table->unsignedInteger('completed_runs')->default(0);
            $table->unsignedInteger('answered_questions')->default(0);
            $table->json('level_best_scores')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_test_stats');
    }
};