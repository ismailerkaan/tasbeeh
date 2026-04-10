<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_zikrs', function (Blueprint $table): void {
            $table->id();
            $table->date('date')->index();
            $table->string('locale', 10)->nullable()->index();
            $table->string('title')->default('Bugunun zikri');
            $table->foreignId('zikir_id')->nullable()->constrained('zikirs')->nullOnDelete();
            $table->text('transliteration')->nullable();
            $table->text('meaning')->nullable();
            $table->text('virtue_short')->nullable();
            $table->unsignedInteger('count_suggestion')->nullable();
            $table->text('share_text')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['date', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_zikrs');
    }
};
