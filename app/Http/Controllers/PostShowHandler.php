<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use App\Http\Resources\PostResource;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/posts/{id}',
    summary: 'Show a single post with its comments',
    tags: ['Posts'],
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
                        new OA\Property(property: 'title', type: 'string'),
                        new OA\Property(property: 'content', type: 'string'),
                        new OA\Property(property: 'user', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                        ]),
                        new OA\Property(property: 'category', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                        ]),
                        new OA\Property(property: 'comment_count', type: 'integer'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                    ]),
                    new OA\Property(property: 'comments', type: 'array', items: new OA\Items(type: 'object', properties: [
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
class PostShowHandler extends Handler
{
    public function __construct(private readonly PostRepositoryInterface $posts) {}

    public function __invoke($id)
    {
        $post = $this->posts->find($id);
        $post->load(['user', 'category', 'comments.user']);
        $comments = $post->comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
                'created_at' => $comment->created_at,
            ];
        });
        return response()->json([
            'data' => new PostResource($post),
            'comments' => $comments,
        ]);
    }
} 