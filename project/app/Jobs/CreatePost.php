<?php

namespace App\Jobs;

use App\Events\PostWasCreated;
use App\Models\Post;

class CreatePost extends Job
{
    protected $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $post = Post::create($this->payload);

        event(new PostWasCreated($post));

        return $post;
    }
}
