<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'industry',
        'headquarters',
        'size',
        'website_url',
        'logo_url',
        'founded_year',
        'rating',
        'description',
        'perks',
    ];

    protected $casts = [
        'perks' => 'array',
        'rating' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (Company $company) {
            if (blank($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function publishedJobs()
    {
        return $this->jobs()->published();
    }

    public function reviews()
    {
        return $this->hasMany(CompanyReview::class);
    }

    public function refreshAggregatedRating(): void
    {
        $average = $this->reviews()->avg('overall_rating');
        if ($average !== null) {
            $this->rating = round($average, 1);
            $this->saveQuietly();
        }
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
