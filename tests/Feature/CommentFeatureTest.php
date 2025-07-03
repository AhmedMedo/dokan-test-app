<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;


    public function test_authenticated_user_can_post_comment()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);
        $payload = [
            'content' => 'Feature comment!'
        ];
        $response = $this->actingAs($user)->postJson('/api/posts/' . $post->id . '/comments', $payload);
        $response->assertCreated()->assertJsonPath('data.content', 'Feature comment!');
    }
}
