<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileImageRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserByViewsResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function indexByViews(): JsonResponse
    {
        $users = $this->userService->getUsersSortedByPostViews();

        return UserByViewsResource::collection($users)
            ->response()
            ->setStatusCode(200);
    }

    public function profile(): JsonResponse
    {
        return (new UserResource(Auth::user()))
            ->response()
            ->setStatusCode(200);
    }

    public function uploadProfileImage(ProfileImageRequest $request): JsonResponse
    {
        $user = Auth::user();
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $path;
        $user->save();

        return (new UserResource($user))
            ->additional([
                'message' => 'Profile image uploaded successfully.'
            ])
            ->response()
            ->setStatusCode(200);
    }
}
