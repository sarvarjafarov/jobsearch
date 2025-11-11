<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class SeoMeta extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'seo_meta';

    protected $fillable = [
        'route_name',
        'path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_image',
        'schema_json',
        'noindex',
    ];

    protected $casts = [
        'schema_json' => 'array',
        'noindex' => 'boolean',
    ];
}
