<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('religious_special_days', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->date('event_date');
            $table->string('hijri_date')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->text('description');
            $table->json('recommendations');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('religious_special_days');
    }
};
