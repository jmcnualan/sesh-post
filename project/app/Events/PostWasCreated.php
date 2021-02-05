<?php

namespace App\Events;

use App\Models\Post;

class PostWasCreated extends Event
{
    public $post;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
