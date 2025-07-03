<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Put(
    path: '/api/posts/{id}',
    summary: 'Update a post (owner only)',
    tags: ['Posts'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    requestBody: new OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
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
            response: 200,
            description: 'Updated',
            content: new OA\JsonContent(ref: null)
        ),
    ]
)]
class PostUpdateHandler extends Handler
{
    public function __construct(private readonly PostRepositoryInterface $posts) {}

    public function __invoke(Request $request, $id)
    {
        $post = $this->posts->find($id);
        $this->authorize('update', $post);
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
        ]);
        $post = $this->posts->update($post, $data);
        $post->load(['user', 'category', 'comments']);
        return new PostResource($post);
    }
} 