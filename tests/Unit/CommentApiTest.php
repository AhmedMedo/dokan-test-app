<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_post_comment_authenticated()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $payload = [
            'content' => 'Great post!'
        ];
        $response = $this->actingAs($user)->postJson('/api/posts/' . $post->id . '/comments', $payload);
        $response->assertCreated()->assertJsonPath('data.content', 'Great post!');
    }

    public function test_create_comment_validation_error()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $payload = [
            'content' => '', // invalid
        ];
        $response = $this->actingAs($user)->postJson('/api/posts/' . $post->id . '/comments', $payload);
        $response->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['content']]);
    }
}
