<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\FollowController; // << YENİ EKLENDİ: FollowController'ı kullanmak için >>

// Ana Dizin (/) Rotası - localhost:8000 adresine gidildiğinde burası çalışır
// Kullanıcıyı doğrudan /home rotasına yönlendirir
Route::get('/', function () {
    return redirect()->route('home');
});

// Laravel'in varsayılan kimlik doğrulama rotaları (kayıt, giriş, şifre sıfırlama vb.)
Auth::routes();

// Anasayfa rotası
Route::get('/home', [HomeController::class, 'index'])->name('home');

// --- Kullanıcı Profili Rotaları ---
// Bu rotalar, ProfileController'daki yeni metodları kullanır.
// Kendi profilini görüntüleme ve düzenleme rotaları (sadece giriş yapmış kullanıcılar erişebilir)
Route::middleware('auth')->group(function () {
    // Kendi profilini görüntüleme
    // Örn: yourdomain.com/profile
    Route::get('/profile', [ProfileController::class, 'showOwnProfile'])->name('profile.show.own');

    // Profil düzenleme formunu gösterme
    // Örn: yourdomain.com/profile/edit
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Profil bilgilerini güncelleme işlemi (PUT metodu ile)
    // Bu rota form gönderildiğinde çalışır
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Başka bir kullanıcının profilini görüntüleme (username ile)
// Örn: yourdomain.com/users/kullaniciadi
// Bu rota, eski '/profile/{id}' ve '/users/{user}' rotalarının profil gösterme işlevini üstlenir.
// {user:username} yapısı, User modelini 'username' sütununa göre otomatik bulur.
Route::get('/users/{user:username}', [ProfileController::class, 'show'])->name('profile.show');

// --- Takip Sistemi Rotaları (YENİ EKLENDİ) ---
// Bu rotalar da sadece giriş yapmış kullanıcılar tarafından erişilebilir olmalı.
Route::middleware('auth')->group(function () {
    // Bir kullanıcıyı takip etme rotası
    // Örn: POST /users/kullaniciadi/follow
    Route::post('/users/{user:username}/follow', [FollowController::class, 'follow'])->name('follow.follow');

    // Bir kullanıcının takibini bırakma rotası
    // Örn: DELETE /users/kullaniciadi/unfollow
    Route::delete('/users/{user:username}/unfollow', [FollowController::class, 'unfollow'])->name('follow.unfollow');
});


// --- Mevcut Diğer Rotaların ---
// Bu kısımlar senin daha önce kullandığın ve çalışmasını istediğin rotalar.
Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);
// Route::resource('likes', LikeController::class); // Eğer LikeController'ın varsa bu kalsın
// Route::resource('follows', FollowController::class); // Bu satırın resource olarak kalmasına gerek yok, yukarıda özel olarak tanımladık.
// Route::resource('messages', MessageController::class); // Eğer MessageController'ın varsa kalsın

// Belirli gönderi ve yorum aksiyonları
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/posts/{post}/like', [PostLikeController::class, 'like'])->name('posts.like')->middleware('auth');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// --- ESKİ VE ARTIK GEREKSİZ OLAN PROFİL ROTASI ---
// Bu rota, yeni '/users/{user:username}' rotası ve ProfileController'daki değişiklikler nedeniyle
// artık gerekli değildir ve çakışmalara yol açabilir. Bu satırı silebilirsin veya yorum satırı yapabilirsin.
// Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
// Not: Eğer UserController'daki 'show' metodu da profil gösterme işlevi görüyorsa, o da artık gereksiz olabilir.
// Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
