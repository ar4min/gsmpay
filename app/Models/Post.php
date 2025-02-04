<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $attributes = [
        'views_count' => 0,
    ];

    protected $fillable = ['user_id', 'title', 'body', 'views_count'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
