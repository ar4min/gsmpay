<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserByViewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total_views' => $this->posts_sum_views_count ?? 0,
        ];
    }
}
