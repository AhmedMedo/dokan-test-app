<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/api/comments/{id}',
    summary: 'Delete a comment (owner only)',
    tags: ['Comments'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    responses: [
        new OA\Response(response: 204, description: 'Deleted'),
    ]
)]
class CommentDeleteHandler extends Handler
{
    public function __construct(private readonly CommentRepositoryInterface $comments) {}

    public function __invoke($id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete', $comment);
        $this->comments->delete($comment);
        return response()->json(['message' => 'Comment deleted'], 204);
    }
} 