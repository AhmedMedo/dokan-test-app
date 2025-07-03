<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    use AuthorizesRequests;

    protected CommentRepositoryInterface $comments;
    protected PostRepositoryInterface $posts;

    public function __construct(CommentRepositoryInterface $comments, PostRepositoryInterface $posts)
    {
        $this->comments = $comments;
        $this->posts = $posts;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, $id)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['post_id'] = $id;
        $comment = $this->comments->create($data);
        $comment->load('user');
        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('update', $comment);
        $data = $request->validate([
            'content' => 'sometimes|required|string',
        ]);
        $comment = $this->comments->update($comment, $data);
        $comment->load('user');
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = $this->comments->find($id);
        $this->authorize('delete', $comment);
        $this->comments->delete($comment);
        return response()->json(['message' => 'Comment deleted'], 204);
    }
}
