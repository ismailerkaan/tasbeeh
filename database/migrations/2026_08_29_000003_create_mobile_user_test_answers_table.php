<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_user_test_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mobile_user_test_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('test_question_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('question_order')->default(0);
            $table->string('selected_option_id', 20);
            $table->string('correct_option_id', 20)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('score_earned')->default(0);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->index(['mobile_user_test_run_id', 'question_order'], 'muta_run_order_idx');
            $table->index(['test_question_id', 'is_correct'], 'muta_question_correct_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_test_answers');
    }
};