<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use App\Http\Resources\CommentResource;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/comments',
    summary: 'List all comments',
    tags: ['Comments'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object', properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'content', type: 'string'),
                        new OA\Property(property: 'user', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                        ]),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                    ])),
                ]
            )
        ),
    ]
)]
class CommentListHandler extends Handler
{
    public function __construct(private readonly CommentRepositoryInterface $comments) {}

    public function __invoke()
    {
        $comments = $this->comments->all()->load('user');
        return CommentResource::collection($comments);
    }
} 