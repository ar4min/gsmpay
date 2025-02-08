<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUsersSortedByPostViews()
    {
        return User::withSum('posts', 'views_count')
            ->orderBy('posts_sum_views_count', 'desc')
            ->get();
    }

}
