<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_user_custom_zikirs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mobile_user_id')->constrained()->cascadeOnDelete();
            $table->string('content_id', 100);
            $table->string('name', 255);
            $table->unsignedInteger('target')->default(33);
            $table->unsignedBigInteger('count')->default(0);
            $table->timestamps();

            $table->unique(['mobile_user_id', 'content_id'], 'mobile_user_custom_zikirs_user_content_unique');
            $table->index('mobile_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_custom_zikirs');
    }
};
