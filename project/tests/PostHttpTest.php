<?php

namespace Tests;

use App\Events\PostWasCreated;
use App\Models\Post;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\Testcase;

class PostHttpTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @testdox It can list posts
     *
     * @return void
     */
    public function list(): void
    {
        Post::factory()->count(10)->create();
        $this->json('GET', 'post');

        $this->assertResponseOk();

        $this->response->assertJsonStructure(['data' => [[
            'id',
            'title',
            'content',
            'is_published',
            'created_at',
        ]]]);
    }

    /**
     * @test
     * @testdox It should return resource_not_found on non existent post
     *
     * @return void
     */
    public function notFound(): void
    {
        $this->json('GET', 'post/1');

        $this->assertResponseStatus(404);

        $this->assertEquals(
            'resource_not_found',
            $this->response->json('error')
        );
    }

    /**
     * @test
     * @testdox It can show post details
     *
     * @return void
     */
    public function show(): void
    {
        $post = Post::factory()->create();
        $this->json('GET', 'post/' . $post->id);

        $this->assertResponseOk();

        $this->response->assertJsonStructure(['data' => [
            'id',
            'title',
            'content',
            'is_published',
            'created_at',
        ]]);
    }

    /**
     * Create payload data provider
     *
     * @return array
     */
    public function createPayloadDataProvider(): array
    {
        return [
            [
                json_encode([
                    'title' => Str::random(101),
                    'content' => Str::random(1001),
                    'is_published' => true,
                ]),
                json_encode([
                    'title' => 'The title may not be greater than 100 characters.',
                    'content' => 'The content may not be greater than 1000 characters.',
                ]),
            ],
            [
                json_encode([
                    'title' => Str::random(19),
                    'content' => Str::random(19),
                    'is_published' => true,
                ]),
                json_encode([
                    'title' => 'The title must be at least 20 characters.',
                    'content' => 'The content must be at least 20 characters.',
                ]),
            ],
            [
                json_encode((object) []),
                json_encode([
                    'title' => 'The title field is required.',
                    'content' => 'The content field is required.',
                ]),
            ],
        ];
    }

    /**
     * @test
     * @testdox It can validate create post payload <br/><strong>Payload</strong><br/>$payload<br/><strong>Errors</strong><br/>$errors
     * @dataProvider createPayloadDataProvider
     *
     * @param string $payload
     * @param string $errors
     *
     * @return void
     */
    public function createValidation(string $payload, string $errors): void
    {
        $this->json('POST', 'post/', json_decode($payload, true));

        $this->assertResponseStatus(422);

        $this->response->assertJsonValidationErrors(json_decode($errors, true));
    }

    /**
     * @test
     * @testdox It can create a post
     *
     * @return void
     */
    public function store(): void
    {
        Event::fake([PostWasCreated::class]);
        $payload = [
            'title' => Str::random(21),
            'content' => Str::random(21),
            'is_published' => true,
        ];

        $this->json('POST', 'post/', $payload);

        $this->assertResponseStatus(201);

        $this->seeInDatabase(
            (new Post())->getTable(),
            $payload
        );

        $this->response->assertJsonStructure(['data' => [
            'id',
            'title',
            'content',
            'is_published',
            'created_at',
        ]]);

        Event::assertDispatched(PostWasCreated::class);
    }
}
