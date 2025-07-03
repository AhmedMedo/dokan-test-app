<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/posts',
    summary: 'List all posts with user & category names, and comment count',
    tags: ['Posts'],
    parameters: [
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'user_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'category_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
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
                    new OA\Property(property: 'meta', type: 'object', properties: [
                        new OA\Property(property: 'current_page', type: 'integer'),
                        new OA\Property(property: 'last_page', type: 'integer'),
                        new OA\Property(property: 'per_page', type: 'integer'),
                        new OA\Property(property: 'total', type: 'integer'),
                    ]),
                ]
            )
        ),
    ]
)]
class PostListHandler extends Handler
{
    public function __construct(private readonly PostRepositoryInterface $posts) {}

    public function __invoke(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        $query = $this->posts->queryWithRelations(['user', 'category', 'comments']);
        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }
        $posts = $query->paginate($perPage);
        return PostResource::collection($posts);
    }
} 