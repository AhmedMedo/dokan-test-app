<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public function all()
    {
        return Comment::all();
    }

    public function find($id)
    {
        return Comment::query()->findOrFail($id);
    }

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function update(Comment $comment, array $data)
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
    }

    public function withTrashed()
    {
        return Comment::withTrashed()->get();
    }

    public function onlyTrashed()
    {
        return Comment::onlyTrashed()->get();
    }

    public function restore($id): ?Comment
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->restore();
        return $comment;
    }

    public function forceDelete($id): void
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();
    }
}
