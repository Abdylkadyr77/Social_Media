<!DOCTYPE html>
<html>
<head>
    <title>{{ $user->name }} - Profil</title> </head>
<body>
    <h1>{{ $user->name }}'in Profili</h1> <h3>Yaptığı Paylaşımlar:</h3> {{-- Blade'in @forelse döngüsü: Eğer $posts dizisi boş değilse her bir paylaşım için döner --}}
    @forelse ($posts as $post)
        {{-- Her bir paylaşım için basit bir div oluşturur --}}
        <div style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px;">
            <p>{{ $post->content }}</p> </div>
    @empty
        {{-- Eğer $posts dizisi boşsa (yani hiç paylaşım yoksa) bu paragrafı gösterir --}}
        <p>Henüz paylaşım yok.</p>
    @endforelse

    {{-- Tüm gönderilerin listelendiği sayfaya geri dönmek için bir bağlantı --}}
    <a href="{{ route('posts.index') }}">← Geri dön</a>
</body>
</html>