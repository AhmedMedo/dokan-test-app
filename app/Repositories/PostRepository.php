<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function all()
    {
        return Post::all();
    }

    public function find($id)
    {
        return Post::query()->findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    public function withTrashed()
    {
        return Post::withTrashed()->get();
    }

    public function onlyTrashed()
    {
        return Post::onlyTrashed()->get();
    }

    public function restore($id): ?Post
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return $post;
    }

    public function forceDelete($id): void
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();
    }
}
