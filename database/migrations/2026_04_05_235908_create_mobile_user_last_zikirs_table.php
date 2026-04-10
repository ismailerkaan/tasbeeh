<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_user_last_zikirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobile_user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('content_id');
            $table->string('name');
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_last_zikirs');
    }
};
