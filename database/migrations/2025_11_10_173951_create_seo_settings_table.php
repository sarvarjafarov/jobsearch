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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('default_title')->nullable();
            $table->text('default_description')->nullable();
            $table->string('default_keywords')->nullable();
            $table->string('default_og_image')->nullable();
            $table->string('default_twitter_image')->nullable();
            $table->string('favicon_path')->nullable();
            $table->json('global_schema')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
