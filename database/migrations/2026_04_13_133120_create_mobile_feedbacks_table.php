<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('user_identifier', 100)->nullable()->index();
            $table->string('full_name', 255);
            $table->text('message');
            $table->string('fcm_token', 255)->nullable();
            $table->string('platform', 20)->nullable();
            $table->string('device_model', 255)->nullable();
            $table->string('os_version', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_feedbacks');
    }
};
