<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private Collection $posts;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->posts = Post::factory()->count(10)->create();
        $this->user = User::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('posts.index'));

        $response->assertOk();
    }

    public function testShow(): void
    {
        $post = $this->posts->first();
        $response = $this->get(route('posts.show', $post));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('posts.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        $post = Post::factory()->for($this->user, 'author')->create();
        $response = $this->actingAs($this->user)->get(route('posts.edit', $post));
        $response->assertOk();
    }

    public function testStoreAsGuest(): void
    {
        $body = [
            'title' => 'title',
            'content' => 'content',
        ];

        $response = $this->post(route('posts.store'), $body);

        $response->assertForbidden();
    }

    public function testStore(): void
    {
        $body = [
            'title' => 'title',
            'content' => 'content',
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('posts.store'), $body);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertTrue(
            $this->user->posts()->where($body)->exists()
        );
    }

    public function testUpdate(): void
    {
        $post = Post::factory()->for($this->user, 'author')->create();
        $body = [
            'title' => 'title',
            'content' => 'content',
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('posts.update', $post), $body);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $post->refresh();

        $this->assertEquals($body['title'], $post->title);
    }

    public function testDestroy(): void
    {
        $post = Post::factory()->for($this->user, 'author')->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertModelMissing($post);
    }

    public function testDestroyNotAuthor(): void
    {
        $post = Post::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('posts.destroy', $post));

        $response->assertForbidden();

        $this->assertModelExists($post);
    }

    public function testAttachImage(): void
    {
        Storage::fake();

        $post = Post::factory()->for($this->user, 'author')->create();

        $body = [
            'image' => UploadedFile::fake()->image('img.jpg'),
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('posts.attach_image', $post), $body);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $post->refresh();

        $this->assertNotEmpty($post->image_filepath);

        Storage::assertExists($post->image_filepath);
    }
}
