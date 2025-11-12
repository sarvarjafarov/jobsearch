<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('jobs')
            && Schema::hasColumn('jobs', 'queue')
            && Schema::hasColumn('jobs', 'payload')
            && ! Schema::hasTable('queue_jobs')
        ) {
            Schema::rename('jobs', 'queue_jobs');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            ! Schema::hasTable('jobs')
            && Schema::hasTable('queue_jobs')
            && Schema::hasColumn('queue_jobs', 'queue')
            && Schema::hasColumn('queue_jobs', 'payload')
        ) {
            Schema::rename('queue_jobs', 'jobs');
        }
    }
};
