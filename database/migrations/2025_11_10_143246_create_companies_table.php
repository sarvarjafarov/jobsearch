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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('industry')->nullable();
            $table->string('headquarters')->nullable();
            $table->string('size')->nullable();
            $table->string('website_url')->nullable();
            $table->string('logo_url')->nullable();
            $table->integer('founded_year')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->text('description')->nullable();
            $table->json('perks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
