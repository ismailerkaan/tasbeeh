<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hadises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hadis_category_id')->constrained()->cascadeOnDelete();
            $table->string('source');
            $table->text('hadis');
            $table->text('turkce_meali');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hadises');
    }
};
