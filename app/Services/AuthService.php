<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function registerUser(array $data): string
    {
        $user = User::create([
            'name' => Arr::get($data, 'name'),
            'phone' => Arr::get($data, 'phone'),
            'password' => Hash::make(Arr::get($data, 'password')),
        ]);

        return JWTAuth::fromUser($user);
    }

    public function loginUser(array $credentials): ?string
    {
        $token = auth()->attempt($credentials);

        return $token ?: null;
    }
}
