<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/api/posts/{id}',
    summary: 'Delete a post (owner only)',
    tags: ['Posts'],
    security: [['sanctum' => []]],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    responses: [
        new OA\Response(response: 204, description: 'Deleted'),
    ]
)]
class PostDeleteHandler extends Handler
{
    public function __construct(private readonly PostRepositoryInterface $posts) {}

    public function __invoke($id)
    {
        $post = $this->posts->find($id);
        $this->authorize('delete', $post);
        $this->posts->delete($post);
        return response()->json(['message' => 'Post deleted'], 204);
    }
} 