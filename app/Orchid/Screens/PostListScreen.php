<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PostListScreen extends Screen
{
    public array $permission = ['platform.posts'];

    public function query(): iterable
    {
        return [
            'posts' => Post::orderByDesc('published_at')->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Blog Posts';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Create post')
                ->icon('bs.plus-circle')
                ->route('platform.posts.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('posts', [
                TD::make('title', 'Title')
                    ->sort()
                    ->render(fn (Post $post) => Link::make($post->title)->route('platform.posts.edit', $post)),
                TD::make('author_name', 'Author')->defaultHidden(),
                TD::make('published_at', 'Published')
                    ->render(fn (Post $post) => optional($post->published_at)->format('M d, Y') ?? 'Draft')
                    ->sort(),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Post $post) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')
                                ->route('platform.posts.edit', $post)
                                ->icon('bs.pencil'),
                            Button::make('Delete')
                                ->confirm('Delete this post?')
                                ->icon('bs.trash3')
                                ->method('remove', [
                                    'post' => $post->id,
                                ]),
                        ])),
            ]),
        ];
    }

    public function remove(Post $post)
    {
        $post->delete();
        Toast::info('Post deleted.');
    }
}
