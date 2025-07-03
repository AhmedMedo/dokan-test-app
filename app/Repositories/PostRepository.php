<?php

namespace App\Repositories;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection;
    public function find(int|string $id): Post;
    public function create(array $data): Post;
    public function update(Post $post, array $data): Post;
    public function delete(Post $post): void;
    public function withTrashed(): \Illuminate\Database\Eloquent\Collection;
    public function onlyTrashed(): \Illuminate\Database\Eloquent\Collection;
    public function restore(int|string $id): ?Post;
    public function forceDelete(int|string $id): void;
    public function queryWithRelations(array $relations = []): \Illuminate\Database\Eloquent\Builder;
}

class PostRepository implements PostRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Post::all();
    }

    public function find(int|string $id): Post
    {
        return Post::query()->findOrFail($id);
    }

    public function create(array $data): Post
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

    public function withTrashed(): \Illuminate\Database\Eloquent\Collection
    {
        return Post::withTrashed()->get();
    }

    public function onlyTrashed(): \Illuminate\Database\Eloquent\Collection
    {
        return Post::onlyTrashed()->get();
    }

    public function restore(int|string $id): ?Post
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return $post;
    }

    public function forceDelete(int|string $id): void
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();
    }

    public function queryWithRelations(array $relations = []): \Illuminate\Database\Eloquent\Builder
    {
        return Post::with($relations);
    }
}
