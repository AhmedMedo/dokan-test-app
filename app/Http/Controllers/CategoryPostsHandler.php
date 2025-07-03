<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepositoryInterface;
use App\Http\Resources\PostResource;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/categories/{id}/posts',
    summary: 'List posts by category',
    tags: ['Categories'],
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
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object', properties: [
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
                    ])),
                ]
            )
        ),
    ]
)]
class CategoryPostsHandler extends Handler
{
    public function __construct(private readonly CategoryRepositoryInterface $categories) {}

    public function __invoke($id)
    {
        $category = $this->categories->find($id);
        $posts = $category->posts()->with(['user', 'category', 'comments'])->get();
        return PostResource::collection($posts);
    }
} 