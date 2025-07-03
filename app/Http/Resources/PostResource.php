<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            'comment_count' => $this->comments->count(),
            'created_at' => $this->created_at,
        ];
    }

    public static function collection($resource)
    {
        $response = parent::collection($resource);
        if (method_exists($resource, 'total')) {
            $response->additional([
                'meta' => [
                    'current_page' => $resource->currentPage(),
                    'last_page' => $resource->lastPage(),
                    'per_page' => $resource->perPage(),
                    'total' => $resource->total(),
                ],
            ]);
        }
        return $response;
    }
}
