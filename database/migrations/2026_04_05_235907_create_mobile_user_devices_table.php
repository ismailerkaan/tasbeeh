<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mobile_user_devices')) {
            return;
        }

        Schema::create('mobile_user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobile_user_id')->constrained()->cascadeOnDelete();
            $table->string('fcm_token')->unique();
            $table->string('device_name')->nullable();
            $table->string('device_model')->nullable();
            $table->string('os', 20)->nullable();
            $table->string('os_version', 50)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_user_devices');
    }
};
