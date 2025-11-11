<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class SeoSetting extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'site_name',
        'default_title',
        'default_description',
        'default_keywords',
        'default_og_image',
        'default_twitter_image',
        'favicon_path',
        'global_schema',
    ];

    protected $casts = [
        'global_schema' => 'array',
    ];
}
