<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $posts = $this->postService->getUserPosts($perPage);

        return PostResource::collection($posts)
            ->response()
            ->setStatusCode(200);
    }


    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->storePost($request->validated());
        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id, Request $request): JsonResponse
    {
        $post = $this->postService->getPostById($id);
        $this->postService->incrementView($post, $request->ip());
        return (new PostResource($post))
            ->response()
            ->setStatusCode(200);
    }
}
