<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Post modelini kullandığın için gerekli
use App\Http\Controllers\Controller; // Controller sınıfını extend ettiğin için gerekli
use Illuminate\Support\Facades\Auth; // auth()->id() kullandığın için bu da gerekli
use Illuminate\Support\Facades\Storage; // Storage facade'ini kullanmak için gerekli

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('comments')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
        return "Post oluşturma formu burada olacak.";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Post::create([
            'user_id' => Auth::id(), // auth()->id() yerine Auth::id() kullandım, daha yaygın ve doğru kullanım
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // Tek bir gönderiyi göstermek için kullanılabilir
        return view('posts.show', compact('post'));
        return "Gönderi detayı burada olacak: - " . $post->content;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // Gönderiyi düzenleme formunu gösteren bir görünüm döndürebilirsin
        return view('posts.edit', compact('post'));
        return "Gönderi düzenleme formu burada olacak: - " . $post->content;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // Gönderiyi düzenleme işlemi
        $request->validate([
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $post->image; // Mevcut resmi koru

        if ($request->hasFile('image')) {
            // Yeni resim yüklendiğinde eski resmi sil
            if ($post->image) {
                Storage::delete('public/' . $post->image); // Bu satırı kullanmak için 'use Illuminate\Support\Facades\Storage;' eklemelisin
            }
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $post->update([
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // **Yeni eklenen kod buraya entegre edildi.**
        // Eski koddaki $post parametresi zaten var, bu yüzden $id'ye gerek yok.
        // Yetki kontrolü ve resim silme işlemleri burada yapılacak.

        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'Bu gönderiyi silmeye yetkiniz yok.');
        }

        if ($post->image) {
            Storage::delete('public/' . $post->image);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Gönderi silindi.');
    }
}