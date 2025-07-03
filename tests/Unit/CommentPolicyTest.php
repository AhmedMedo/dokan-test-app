<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_owner_can_update_and_delete()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $updatePayload = ['content' => 'Updated comment'];
        $updateResponse = $this->actingAs($user)->putJson('/api/comments/' . $comment->id, $updatePayload);
        $updateResponse->assertOk()->assertJsonPath('data.content', 'Updated comment');
        $deleteResponse = $this->actingAs($user)->deleteJson('/api/comments/' . $comment->id);
        $deleteResponse->assertNoContent();
    }

    public function test_non_owner_cannot_update_or_delete()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id, 'category_id' => $category->id]);
        $comment = Comment::factory()->create(['user_id' => $owner->id, 'post_id' => $post->id]);
        $updatePayload = ['content' => 'Hacked comment'];
        $updateResponse = $this->actingAs($other)->putJson('/api/comments/' . $comment->id, $updatePayload);
        $updateResponse->assertForbidden();
        $deleteResponse = $this->actingAs($other)->deleteJson('/api/comments/' . $comment->id);
        $deleteResponse->assertForbidden();
    }
}
