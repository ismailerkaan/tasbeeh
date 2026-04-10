<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dua_category_id')->constrained()->cascadeOnDelete();
            $table->string('source');
            $table->text('dua');
            $table->text('turkce_meali');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duas');
    }
};
