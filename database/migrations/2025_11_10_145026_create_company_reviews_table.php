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
        Schema::create('company_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_role')->nullable();
            $table->string('employment_type')->nullable();
            $table->unsignedTinyInteger('culture_rating');
            $table->unsignedTinyInteger('compensation_rating');
            $table->unsignedTinyInteger('leadership_rating');
            $table->unsignedTinyInteger('work_life_rating');
            $table->unsignedTinyInteger('growth_rating');
            $table->unsignedTinyInteger('overall_rating');
            $table->boolean('would_recommend')->default(true);
            $table->text('highlights')->nullable();
            $table->text('challenges')->nullable();
            $table->text('advice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_reviews');
    }
};
