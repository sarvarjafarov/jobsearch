<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->string('q')->toString();

        $posts = Post::query()
            ->published()
            ->when($query, fn ($builder) => $builder->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('body', 'like', "%{$query}%");
            }))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'query' => $query,
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->published_at && $post->published_at->isPast(), 404);

        $morePosts = Post::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'morePosts' => $morePosts,
        ]);
    }
}
