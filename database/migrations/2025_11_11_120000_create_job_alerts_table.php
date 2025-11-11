<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('keyword')->nullable();
            $table->string('company')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['email', 'keyword', 'company', 'location'], 'job_alert_unique_filter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
    }
};
