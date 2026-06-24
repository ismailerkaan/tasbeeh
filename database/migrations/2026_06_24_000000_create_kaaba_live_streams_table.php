<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kaaba_live_streams', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->default('Kâbe Canlı Yayını');
            $table->text('youtube_url');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kaaba_live_streams');
    }
};
