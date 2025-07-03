<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

class PostFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $payload = [
            'title' => 'Feature Test Post',
            'content' => 'Feature test content',
            'category_id' => $category->id,
        ];
        $response = $this->actingAs($user)->postJson('/api/posts', $payload);
        $response->assertCreated()->assertJsonPath('data.title', 'Feature Test Post');
    }

    public function test_unauthenticated_user_cannot_create_post()
    {
        $category = Category::factory()->create();
        $payload = [
            'title' => 'Feature Test Post',
            'content' => 'Feature test content',
            'category_id' => $category->id,
        ];
        $response = $this->postJson('/api/posts', $payload);
        $response->assertUnauthorized();
    }

    public function test_view_post_with_comments()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $commentUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $commentUser->id, 'post_id' => $post->id, 'content' => 'Nice feature!']);
        $response = $this->getJson('/api/posts/' . $post->id);
        $response->assertOk()->assertJsonStructure(['data', 'comments']);
        $this->assertEquals('Nice feature!', $response->json('comments.0.content'));
    }
}
