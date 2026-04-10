<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zikirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zikir_category_id')->constrained()->cascadeOnDelete();
            $table->string('zikir');
            $table->text('anlami');
            $table->text('fazileti');
            $table->unsignedInteger('hedef');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zikirs');
    }
};
