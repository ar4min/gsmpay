<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function usersByViews(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $users = User::with('posts')
            ->withSum('posts as total_views', 'views_count')
            ->orderByDesc('total_views')
            ->paginate($perPage, ['id', 'name', 'email', 'phone', 'profile_image']);

        return response()->json($users);
    }
}
