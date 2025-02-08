<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\TokenResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): TokenResource|JsonResponse
    {
        $token = $this->authService->registerUser($request->validated());

        return (new TokenResource($token));
    }

    public function login(LoginRequest $request): TokenResource|JsonResponse
    {
        $token = $this->authService->loginUser($request->validated());

        if (!$token) {
            return response()->json([
                'data' => [
                    'error' => 'Unauthorized'
                ]
            ], 401);
        }

        return (new TokenResource($token));
    }
}
