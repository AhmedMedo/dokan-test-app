<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_list_posts_by_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $otherCategory = Category::factory()->create();
        Post::factory()->count(2)->create(['user_id' => $user->id, 'category_id' => $category->id]);
        Post::factory()->count(1)->create(['user_id' => $user->id, 'category_id' => $otherCategory->id]);
        $response = $this->getJson('/api/categories/' . $category->id . '/posts');
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'title', 'content', 'user', 'category', 'comment_count', 'created_at']]]);
        $this->assertCount(2, $response->json('data'));
    }
}
