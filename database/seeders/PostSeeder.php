<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'How to stand out with a remote resume',
                'excerpt' => 'Practical tips from Jobify coaches on highlighting async collaboration, deep work, and outcomes.',
                'cover_image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
                'body' => "Remote-friendly teams care about documentation, ownership, and communication. In this guide we unpack the exact bullet formulas we use with Jobify candidates and provide a checklist you can pair with the new Resume Builder.",
            ],
            [
                'title' => 'Inside the Jobify scraping pipeline',
                'excerpt' => 'A peek at how we monitor and refresh verified vacancies across the ecosystem.',
                'cover_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'body' => "High-signal roles require high-signal data. Our sourcing pipeline continuously syncs with curated boards like jobsearch.az, deduplicates entries, and flags companies for the partnerships team.",
            ],
            [
                'title' => 'Interview best practices in 2025',
                'excerpt' => 'Fresh expectations from hiring managers in product, data, and GTM.',
                'cover_image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
                'body' => "From async take-homes to AI-assisted screenings, interviews are evolving quickly. Here are the rituals we see in top-performing orgs and how to prepare with Jobify prompts.",
            ],
        ];

        foreach ($posts as $entry) {
            Post::updateOrCreate(
                ['slug' => Str::slug($entry['title'])],
                array_merge($entry, [
                    'author_name' => 'Jobify Editorial',
                    'published_at' => now()->subDays(rand(1, 20)),
                ])
            );
        }
    }
}
