<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pending review',
        self::STATUS_PUBLISHED => 'Published',
    ];

    /**
     * Allow mass-assignment for the primary job attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position',
        'company',
        'description',
        'published_date',
        'deadline_date',
        'location',
        'apply_url',
        'status',
    ];

    /**
     * Automatically cast date fields to Carbon instances.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_date' => 'date',
        'deadline_date' => 'date',
        'status' => 'string',
    ];

    /**
     * Scope a query to only include published roles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }
}
