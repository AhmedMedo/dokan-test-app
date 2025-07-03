<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    use AuthorizesRequests;

    protected $posts;
    protected $categories;

    public function __construct(PostRepository $posts, CategoryRepository $categories)
    {
        $this->posts = $posts;
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = $this->posts->all()->load(['user', 'category', 'comments']);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $post = $this->posts->create($data);
        $post->load(['user', 'category', 'comments']);
        return (new PostResource($post))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = $this->posts->find($id);
        $post->load(['user', 'category', 'comments.user']);
        return (new PostResource($post))->additional([
            'comments' => \App\Http\Resources\CommentResource::collection($post->comments)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = $this->posts->find($id);
        $this->authorize('update', $post);
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);
        $post = $this->posts->update($post, $data);
        $post->load(['user', 'category', 'comments']);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = $this->posts->find($id);
        $this->authorize('delete', $post);
        $this->posts->delete($post);
        return response()->json(['message' => 'Post deleted'], 204);
    }
}
