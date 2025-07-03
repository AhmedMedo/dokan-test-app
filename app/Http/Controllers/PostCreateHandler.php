<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Base\Http\Handler;
use App\Http\Requests\StorePostRequest;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/posts',
    summary: 'Create a post',
    tags: ['Posts'],
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['title', 'content', 'category_id'],
            properties: [
                new OA\Property(property: 'title', type: 'string'),
                new OA\Property(property: 'content', type: 'string'),
                new OA\Property(property: 'category_id', type: 'integer'),
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
                ]
            )
        ),
    ]
)]
class PostCreateHandler extends Handler
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
        private readonly CategoryRepositoryInterface $categories
    ) {}

    public function __invoke(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $post = $this->posts->create($data);
        $post->load(['user', 'category', 'comments']);
        return (new PostResource($post))->response()->setStatusCode(201);
    }
} 