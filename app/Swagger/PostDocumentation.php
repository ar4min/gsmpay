<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="GSMPay API",
 *     version="1.0.0",
 *     description="مستندات API پروژه GSMPay"
 * )
 *
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
class PostDocumentation
{
    //
}
