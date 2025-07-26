<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // Projende yüklü değilse bu satırı yorumda bırakmaya devam et

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_picture',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Bir kullanıcının birden fazla gönderisi olabilir.
     * Bu ilişki, kullanıcının profilinde gönderilerini göstermek için kullanılır.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // --- TAKİP SİSTEMİ İÇİN YENİ EKLENEN İLİŞKİLER VE METOTLAR ---

    /**
     * Kullanıcının takip ettiklerini döndürür (yani bu kullanıcının kimleri takip ettiği).
     * 'follows' tablosunda 'follower_id' sütunu bu kullanıcının ID'si olduğunda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following()
    {
        // belongsToMany(İlgili Model, Pivot Tablo Adı, Kaynak Modelin Foreign Key'i, Hedef Modelin Foreign Key'i)
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    /**
     * Kullanıcının takipçilerini döndürür (yani bu kullanıcıyı kimlerin takip ettiği).
     * 'follows' tablosunda 'following_id' sütunu bu kullanıcının ID'si olduğunda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        // belongsToMany(İlgili Model, Pivot Tablo Adı, Kaynak Modelin Foreign Key'i, Hedef Modelin Foreign Key'i)
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    /**
     * Belirli bir kullanıcıyı takip edip etmediğini kontrol eder.
     *
     * @param User $user
     * @return bool
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Kullanıcının tüm gönderilerinin toplam beğeni sayısını döndürür.
     * Bunun doğru çalışması için Post modelinde bir `likes()` ilişkisi olmalı.
     * Ve `Like` modelinizin veya beğeni tablonuzun mevcut olduğunu varsayarız.
     * Eğer beğeni sistemin farklıysa bu metodu daha sonra ayarlarız.
     *
     * @return int
     */
    public function getTotalLikesCount(): int
    {
        return $this->posts->sum(function($post) {
            // Her postun beğeni sayısını toplar. Post modelinde `likes()` ilişkisi gereklidir.
            return $post->likes->count();
        });
    }

    // --- DİĞER MEVCUT İLİŞKİLER ---
    // Eğer yorumlar veya beğeniler gibi başka ilişkiler de eklediysen, buraya kalsın:
    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }

    // Not: Eğer Like modeliniz zaten varsa, Post modeline eklemeniz gereken 'likes' ilişkisi
    // genelde 'hasMany(Like::class)' şeklinde olacaktır.
    // public function likes()
    // {
    //     return $this->hasMany(Like::class);
    // }
}