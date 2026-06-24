<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL can leave tables behind when a DDL statement fails mid-migration.
        Schema::dropIfExists('special_day_sharing_images');
        Schema::dropIfExists('special_day_sharing_campaigns');

        Schema::create('special_day_sharing_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->date('publish_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('special_day_sharing_images', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('special_day_sharing_campaign_id');
            $table->foreign('special_day_sharing_campaign_id', 'sd_share_images_campaign_fk')
                ->references('id')
                ->on('special_day_sharing_campaigns')
                ->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_day_sharing_images');
        Schema::dropIfExists('special_day_sharing_campaigns');
    }
};
