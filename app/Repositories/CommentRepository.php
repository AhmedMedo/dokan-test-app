<?php

namespace App\Repositories;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection;
    public function find(int|string $id): Comment;
    public function create(array $data): Comment;
    public function update(Comment $comment, array $data): Comment;
    public function delete(Comment $comment): void;
    public function withTrashed(): \Illuminate\Database\Eloquent\Collection;
    public function onlyTrashed(): \Illuminate\Database\Eloquent\Collection;
    public function restore(int|string $id): ?Comment;
    public function forceDelete(int|string $id): void;
}

class CommentRepository implements CommentRepositoryInterface
{
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Comment::all();
    }

    public function find(int|string $id): Comment
    {
        return Comment::query()->findOrFail($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function withTrashed(): \Illuminate\Database\Eloquent\Collection
    {
        return Comment::withTrashed()->get();
    }

    public function onlyTrashed(): \Illuminate\Database\Eloquent\Collection
    {
        return Comment::onlyTrashed()->get();
    }

    public function restore(int|string $id): ?Comment
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->restore();
        return $comment;
    }

    public function forceDelete(int|string $id): void
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();
    }
}
