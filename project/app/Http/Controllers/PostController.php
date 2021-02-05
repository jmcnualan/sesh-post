<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post as ResourcesPost;
use App\Jobs\CreatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * List
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $posts = Post::paginate(25);

        return ResourcesPost::collection($posts);
    }

    /**
     * Show
     *
     * @param string $postId
     *
     * @return ResourcesPost
     */
    public function show(string $postId): ResourcesPost
    {
        $post = Post::findOrFail($postId);

        return new ResourcesPost($post);
    }

    /**
     * Create
     *
     * @return ResourcesPost
     */
    public function store(Request $request): ResourcesPost
    {
        $payload = $this->createPayload($request);

        $job = new CreatePost($payload);

        $post = dispatch_now($job);

        return new ResourcesPost($post);
    }

    /**
     * Create payload
     *
     * @param Request $request
     *
     * @return array
     */
    protected function createPayload(Request $request): array
    {
        return $this->validate($request, [
            'title' => ['required', 'min:20', 'max:100'],
            'content' => ['required', 'min:20', 'max:1000'],
            'is_published' => ['required', 'boolean'],
        ]);
    }
}
