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
        return Comment::find($id);
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
} 