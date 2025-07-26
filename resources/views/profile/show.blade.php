@extends('layouts.app') {{-- Kendi ana layout dosyanızı kullanın (örn: layouts.app) --}}

@section('content')
<div class="container">
    <div class="profile-header">
        {{-- Profil resmi --}}
        @if ($user->profile_picture)
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}'nin Profil Resmi" class="profile-picture">
        @else
            {{-- Eğer profil resmi yoksa varsayılan bir resim göster --}}
            {{-- public/images/default_profile.png adresinde bir resim olduğundan emin olun --}}
            <img src="{{ asset('images/default_profile.png') }}" alt="Varsayılan Profil Resmi" class="profile-picture">
        @endif
        <h1>{{ $user->name }}</h1>
        <p>@<span class="username">{{ $user->username }}</span></p> {{-- Kullanıcı adını göster --}}
        @if ($user->bio)
            <p class="bio">{{ $user->bio }}</p>
        @endif

        {{-- Takipçi, Takip Edilen ve Beğeni Sayıları (YENİ EKLENDİ) --}}
        <div class="profile-stats">
            <div>
                <strong>{{ $followingCount ?? 0 }}</strong> {{-- Eğer veri gelmezse 0 göster --}}
                <p>Takip Edilen</p>
            </div>
            <div>
                <strong>{{ $followersCount ?? 0 }}</strong> {{-- Eğer veri gelmezse 0 göster --}}
                <p>Takipçi</p>
            </div>
            <div>
                <strong>{{ $totalLikesCount ?? 0 }}</strong> {{-- Eğer veri gelmezse 0 göster --}}
                <p>Beğeniler</p>
            </div>
        </div>

        {{-- Profil düzenleme butonu (kendi profilin ise) veya Takip Et/Takibi Bırak butonu (başka profil ise) --}}
        @auth
            @if (Auth::id() === $user->id)
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Profili Düzenle</a>
            @else
                {{-- Takip Et/Takibi Bırak butonu (YENİ EKLENDİ) --}}
                {{-- Bu form, FollowController'daki follow/unfollow metodlarına POST/DELETE isteği gönderecek --}}
                <form action="{{ $isFollowing ? route('follow.unfollow', $user->username) : route('follow.follow', $user->username) }}" method="POST">
                    @csrf
                    @if ($isFollowing)
                        @method('DELETE') {{-- Takibi bırakmak için DELETE metodu kullanıyoruz --}}
                        <button type="submit" class="btn btn-secondary">Takibi Bırak</button>
                    @else
                        <button type="submit" class="btn btn-primary">Takip Et</button>
                    @endif
                </form>
            @endif
        @endauth
    </div>

    <hr>

    <h2>{{ $user->name }}'nin Gönderileri</h2>
    <div class="user-posts">
        @forelse ($posts as $post)
            <div class="post-card">
                <p>{{ $post->content }}</p>
                @if($post->image_path) {{-- Eğer gönderide resim varsa --}}
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Gönderi Resmi" class="post-image">
                @endif
                <small>Gönderen: {{ $post->user->name }} - {{ $post->created_at->diffForHumans() }}</small>
                {{-- Mevcut post beğenme, yorum yapma, silme/düzenleme butonlarınızı buraya entegre edebilirsiniz --}}
            </div>
        @empty
            <p>Bu kullanıcının henüz bir gönderisi yok.</p>
        @endforelse
    </div>
</div>
@endsection

<style>
    /* Basit CSS stilleri, kendi tasarımınıza göre ayarlayın */
    .container {
        max-width: 900px;
        margin: 20px auto;
        padding: 0 15px;
    }
    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
        border: 3px solid #eee;
    }
    .profile-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .profile-header h1 {
        margin-bottom: 5px;
        color: #333;
    }
    .profile-header .username {
        color: #555;
        font-weight: bold;
    }
    .profile-header .bio {
        color: #666;
        margin-top: 10px;
    }
    /* YENİ EKLENEN: Takipçi, Takip Edilen, Beğeniler sayıları için stil */
    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 30px; /* Sayılar arasındaki boşluk */
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .profile-stats div {
        text-align: center;
    }
    .profile-stats strong {
        font-size: 1.5em;
        color: #333;
        display: block; /* Sayı ve metnin alt alta gelmesi için */
    }
    .profile-stats p {
        margin: 0;
        color: #666;
        font-size: 0.9em;
    }
    /* Mevcut CSS devamı */
    .user-posts {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    .post-card {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .post-card .post-image {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin-top: 10px;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        margin-top: 15px;
        display: inline-block;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    /* YENİ EKLENEN: Takibi Bırak butonu için stil */
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        margin-top: 15px;
        display: inline-block;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    hr {
        border-top: 1px solid #eee;
        margin: 30px 0;
    }
</style>