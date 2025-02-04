<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Info(
 *      title="GSMPay API",
 *      version="1.0.0",
 *      description="مستندات API پروژه GSMPay"
 *  )
 * @OA\Post(
 *     path="/api/posts",
 *     summary="ایجاد یک پست جدید",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","content"},
 *             @OA\Property(property="title", type="string", example="Test Post"),
 *             @OA\Property(property="body", type="string", example="This is a test body.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="پست با موفقیت ایجاد شد",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Test Post"),
 *             @OA\Property(property="body", type="string", example="This is a test content."),
 *             @OA\Property(property="views_count", type="integer", example=0)
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        return response()->json($posts, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json($post, 201);
    }

    public function show($id, Request $request)
    {
        $post = Post::findOrFail($id);

        $ipAddress = $request->ip();
        $cacheKey = "post_view_{$post->id}_{$ipAddress}";

        if (!Cache::has($cacheKey)) {
            $post->increment('views_count');
            Cache::put($cacheKey, true, now()->addDays(3));
        }

        return response()->json($post, 200);
    }
}

