<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    // Yorum ekleme
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return back();
    }

    // Yorum silme
    public function destroy(Comment $comment)
    {
        // Sadece yorumu yazan kullanıcı silebilsin istiyorsan bu kontrolü ekleyebilirsin:
        // if (auth()->id() !== $comment->user_id) {
        //     abort(403); // Yetkisiz erişim
        // }

        $comment->delete();

        return redirect()->back()->with('success', 'Yorum başarıyla silindi.');
    }
}