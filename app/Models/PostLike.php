<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    // Her beğeni bir kullanıcıya aittir
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Her beğeni bir posta aittir
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}