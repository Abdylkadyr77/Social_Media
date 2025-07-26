<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paylaşılanlar</title>
</head>
<body>
    <h1>Paylaşılanlar</h1>

    @foreach ($posts as $post)
        <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
            <strong>Kullanıcı ID:</strong> {{ $post->user_id }}
            <br>
            <strong>İçerik:</strong> {{ $post->content }}

            @if ($post->image)
                <div style="padding-top:10px;">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Fotograf" width="300">
                </div>
            @endif

            {{-- ❤️ Beğenme Butonu --}}
            <div style="margin-top:10px;">
                <form action="{{ route('posts.like', $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @php
                        $liked = $post->likes->contains('user_id', auth()->id());
                    @endphp
                    <button type="submit">
                        {{ $liked ? '❤️ Beğendin' : '🤍 Beğen' }}
                    </button>
                </form>
                <span>{{ $post->likes->count() }} beğeni</span>
            </div>

            {{-- 💬 Yorumlar --}}
            @if ($post->comments->count() > 0)
                <div style="margin-top:10px;">
                    <h4>Yorumlar ({{ $post->comments->count() }})</h4>
                    @foreach ($post->comments as $comment)
                        <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                        @if (Auth::check() && Auth::user()->id == $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yorumu silmek istediğinize emin misiniz?')">Sil</button>
                            </form>
                        @endif
                        <br>
                    @endforeach
                </div>
            @endif

            {{-- 📝 Yorum Gönderme --}}
            <br>
            <h3>Yorum Gönderme Formu</h3>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="content" rows="4" cols="50" placeholder="Yorum yap..." required></textarea><br>
                <button type="submit">Yorum Gönder</button>
            </form>

            {{-- Post Silme Butonu (Yeni Eklenen) --}}
            @if (Auth::check() && Auth::id() === $post->user_id)
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Bu gönderiyi silmek istediğine emin misin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Gönderiyi Sil</button>
                </form>
            @endif
        </div>
    @endforeach
</body>
</html>