<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_user_zikir_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobile_user_id')->constrained()->cascadeOnDelete();
            $table->string('content_id', 100);
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();

            $table->unique(['mobile_user_id', 'content_id']);
            $table->index(['mobile_user_id', 'count']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_zikir_counts');
    }
};
