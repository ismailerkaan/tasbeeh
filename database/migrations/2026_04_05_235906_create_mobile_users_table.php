<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_users', function (Blueprint $table) {
            $table->id();
            $table->string('external_user_id')->unique();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->boolean('is_opt_in')->default(true);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_users');
    }
};
