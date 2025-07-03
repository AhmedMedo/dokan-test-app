<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Put(
    path: '/api/comments/{id}',
    summary: 'Update a comment (owner only)',
    tags: ['Comments'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    requestBody: new OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'content', type: 'string'),
            ],
            type: 'object'
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Updated',
            content: new OA\JsonContent(ref: null)
        ),
    ]
)]
class CommentUpdateHandler extends Handler
{
    public function __construct(private readonly CommentRepositoryInterface $comments) {}

    public function __invoke(Request $request, $id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update', $comment);
        $data = $request->validate([
            'content' => 'sometimes|required|string',
        ]);
        $comment = $this->comments->update($comment, $data);
        $comment->load('user');
        return new CommentResource($comment);
    }
} 