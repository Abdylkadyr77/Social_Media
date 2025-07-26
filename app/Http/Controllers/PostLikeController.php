<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function like(Post $post)
    {
        $user = Auth::user();

        // Zaten beğenmişse, beğeniyi geri al
        $existingLike = PostLike::where('user_id', $user->id)
                                ->where('post_id', $post->id)
                                ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            PostLike::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        return back();
    }
}