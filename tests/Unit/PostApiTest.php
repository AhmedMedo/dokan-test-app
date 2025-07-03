<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_posts()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Post::factory()->count(2)->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $response = $this->getJson('/api/posts');
        $response->assertStatus(200)->assertJsonStructure(['data' => [['id', 'title', 'content', 'user', 'category', 'comment_count', 'created_at']]]);
    }

    public function test_create_post_authenticated()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $payload = [
            'title' => 'Test Post',
            'content' => 'Test content',
            'category_id' => $category->id,
        ];
        $response = $this->actingAs($user)->postJson('/api/posts', $payload);
        $response->assertStatus(201)->assertJsonPath('data.title', 'Test Post');
    }

    public function test_create_post_unauthenticated_fails()
    {
        $category = Category::factory()->create();
        $payload = [
            'title' => 'Test Post',
            'content' => 'Test content',
            'category_id' => $category->id,
        ];
        $response = $this->postJson('/api/posts', $payload);
        $response->assertStatus(401);
    }

    public function test_show_post_with_comments()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $commentUser = User::factory()->create();
        $post->comments()->create(['content' => 'Nice post!', 'user_id' => $commentUser->id]);
        $response = $this->getJson('/api/posts/' . $post->id);
        $response->assertStatus(200)->assertJsonStructure(['data', 'comments']);
    }

    public function test_post_owner_can_update_and_delete()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $updatePayload = ['title' => 'Updated Title'];
        $updateResponse = $this->actingAs($user)->putJson('/api/posts/' . $post->id, $updatePayload);
        $updateResponse->assertOk()->assertJsonPath('data.title', 'Updated Title');
        $deleteResponse = $this->actingAs($user)->deleteJson('/api/posts/' . $post->id);
        $deleteResponse->assertNoContent();
    }

    public function test_non_owner_cannot_update_or_delete()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id, 'category_id' => $category->id]);
        $updatePayload = ['title' => 'Hacked Title'];
        $updateResponse = $this->actingAs($other)->putJson('/api/posts/' . $post->id, $updatePayload);
        $updateResponse->assertForbidden();
        $deleteResponse = $this->actingAs($other)->deleteJson('/api/posts/' . $post->id);
        $deleteResponse->assertForbidden();
    }

    public function test_create_post_validation_error()
    {
        $user = User::factory()->create();
        $payload = [
            'title' => '', // invalid
            'content' => '', // invalid
            // missing category_id
        ];
        $response = $this->actingAs($user)->postJson('/api/posts', $payload);
        $response->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['title', 'content', 'category_id']]);
    }
}
