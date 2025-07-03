<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Base\Http\Handler;
use App\Http\Requests\StoreCommentRequest;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/posts/{id}/comments',
    summary: 'Add comment to post (authenticated)',
    tags: ['Comments'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['content'],
            properties: [
                new OA\Property(property: 'content', type: 'string'),
            ],
            type: 'object'
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Created',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer'),
                    new OA\Property(property: 'content', type: 'string'),
                    new OA\Property(property: 'user', type: 'object', properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'name', type: 'string'),
                    ]),
                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                ]
            )
        ),
    ]
)]
class CommentCreateHandler extends Handler
{
    public function __construct(private readonly CommentRepositoryInterface $comments) {}

    public function __invoke(StoreCommentRequest $request, $id)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['post_id'] = $id;
        $comment = $this->comments->create($data);
        $comment->load('user');
        return (new CommentResource($comment))->response()->setStatusCode(201);
    }
} 