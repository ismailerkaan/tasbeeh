<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_user_test_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mobile_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_level_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->unsignedInteger('best_streak')->default(0);
            $table->boolean('continued_with_ad')->default(false);
            $table->string('ended_reason', 30)->default('completed');
            $table->boolean('completed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['mobile_user_id', 'created_at']);
            $table->index(['test_level_id', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_test_runs');
    }
};