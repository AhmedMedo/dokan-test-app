<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use App\Http\Resources\CommentResource;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/comments/{id}',
    summary: 'Show a single comment',
    tags: ['Comments'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'data', type: 'object', properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'content', type: 'string'),
                        new OA\Property(property: 'user', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                        ]),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                    ]),
                ]
            )
        ),
    ]
)]
class CommentShowHandler extends Handler
{
    public function __construct(private readonly CommentRepositoryInterface $comments) {}

    public function __invoke($id)
    {
        $comment = $this->comments->find($id);
        $comment->load('user');
        return new CommentResource($comment);
    }
} 