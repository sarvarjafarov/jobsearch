<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PostEditScreen extends Screen
{
    public ?Post $post = null;

    public array $permission = ['platform.posts'];

    public function query(Post $post): iterable
    {
        return [
            'post' => $post,
        ];
    }

    public function name(): ?string
    {
        return $this->post && $this->post->exists ? 'Edit Post' : 'Create Post';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Delete this post?')
                ->method('remove')
                ->canSee($this->post && $this->post->exists),

            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('post.title')
                    ->title('Title')
                    ->required()
                    ->maxlength(255),
                Input::make('post.slug')
                    ->title('Slug')
                    ->placeholder('auto-generated')
                    ->help('optional, must be unique'),
                Input::make('post.cover_image')
                    ->title('Cover image URL')
                    ->placeholder('https://'),
                Input::make('post.author_name')
                    ->title('Author name'),
                TextArea::make('post.excerpt')
                    ->title('Excerpt')
                    ->rows(3),
                Quill::make('post.body')
                    ->title('Body')
                    ->required(),
                DateTimer::make('post.published_at')
                    ->title('Publish date')
                    ->allowInput()
                    ->format('Y-m-d H:i'),
            ]),
        ];
    }

    public function save(Post $post, Request $request)
    {
        $validated = $request->validate([
            'post.title' => ['required', 'string', 'max:255'],
            'post.slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,'.$post->id],
            'post.cover_image' => ['nullable', 'url'],
            'post.author_name' => ['nullable', 'string', 'max:255'],
            'post.excerpt' => ['nullable', 'string'],
            'post.body' => ['required', 'string'],
            'post.published_at' => ['nullable', 'date'],
        ]);

        $post->fill($validated['post'])->save();

        Toast::info('Post saved.');

        return redirect()->route('platform.posts.edit', $post);
    }

    public function remove(Post $post)
    {
        $post->delete();
        Toast::info('Post deleted.');
        return redirect()->route('platform.posts.list');
    }
}
