<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostService
{
    public function getUserPosts($perPage = 10)
    {
        return Post::where('user_id', Auth::id())->paginate($perPage);
    }

    public function storePost(array $data)
    {
        return Post::create([
            'user_id' => Auth::id(),
            'title' => Arr::get($data, 'title'),
            'body' => Arr::get($data, 'body'),
        ]);
    }

    public function getPostById($id)
    {
        return Post::findOrFail($id);
    }

    public function incrementView($post, $ip)
    {
        $key = "post_view_{$post->id}_{$ip}";
        if (!Cache::has($key)) {
            $post->increment('views_count');
            Cache::put($key, true, now()->addHours(1));
        }
    }
}
